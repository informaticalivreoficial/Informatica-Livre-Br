<?php

namespace App\Livewire\Dashboard\Financial;

use App\Enums\FinancialStatus;
use App\Enums\FinancialType;
use App\Models\FinancialCategory;
use App\Models\FinancialTransaction;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Transactions extends Component
{
    use WithFileUploads;

    public int $year;
    public int $month;
    public string $statusFilter = '';

    public ?int $editingId = null;
    public string $editingAmount = '';

    public bool $showForm = false;
    public ?int $formId = null;
    public string $formType = 'expense';
    public ?int $formCategoryId = null;
    public string $formDescription = '';
    public string $formAmount = '';
    public int $formYear;
    public int $formMonth;
    public string $formDueDate = '';
    public string $formPaymentMethod = '';
    public string $formEffectiveDate = '';
    public $attachment;
    public ?string $attachmentPath = null;

    public function mount()
    {
        $this->year = now()->year;
        $this->month = now()->month;
        $this->formYear = now()->year;
        $this->formMonth = now()->month;
    }

    public function rules()
    {
        return [
            'formType' => 'required|in:income,expense',
            'formCategoryId' => 'required|exists:financial_categories,id',
            'formDescription' => 'required|string|min:2|max:255',
            'formAmount' => 'required',
            'formYear' => 'required|integer|min:2000|max:2100',
            'formMonth' => 'required|integer|min:1|max:12',
            'formDueDate' => 'nullable|date',
            'formPaymentMethod' => 'nullable|in:pix,boleto,cartao,transferencia,dinheiro',
            'formEffectiveDate' => 'nullable|date',
            'attachment' => 'nullable|file|max:2048|mimes:pdf,jpg,jpeg,png',
        ];
    }

    public function confirmGenerateMonth(): void
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Gerar lançamentos do mês?',
            'text' => "Gerar os lançamentos de {$this->months()[$this->month]}/{$this->year}? Lançamentos já existentes não serão duplicados.",
            'icon' => 'question',
            'confirmButtonText' => 'Sim, gerar',
            'cancelButtonText' => 'Cancelar',
            'confirmEvent' => 'generateMonth',
            'confirmParams' => [],
        ]);
    }

    public function confirmGenerateYear(): void
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Gerar lançamentos do ano?',
            'text' => "Gerar os lançamentos de {$this->year}? Lançamentos já existentes não serão duplicados.",
            'icon' => 'question',
            'confirmButtonText' => 'Sim, gerar',
            'cancelButtonText' => 'Cancelar',
            'confirmEvent' => 'generateYear',
            'confirmParams' => [],
        ]);
    }

    #[On('generateYear')]
    public function generateYear(): void
    {
        $result = FinancialTransaction::generateForYear($this->year);

        $this->dispatch('swal', [
            'title' => 'Lançamentos gerados',
            'text' => "{$result['created']} lançamentos criados e {$result['skipped']} já existentes.",
            'icon' => 'success',
            'timer' => 2500,
            'showConfirmButton' => false,
        ]);
    }

    #[On('generateMonth')]
    public function generateMonth(): void
    {
        $result = FinancialTransaction::generateForMonth($this->year, $this->month);

        $this->dispatch('swal', [
            'title' => 'Lançamentos gerados',
            'text' => "{$result['created']} lançamentos criados e {$result['skipped']} já existentes.",
            'icon' => 'success',
            'timer' => 2500,
            'showConfirmButton' => false,
        ]);
    }

    public function openCreate(string $type = 'expense'): void
    {
        $this->resetForm();
        $this->formType = $type;
        $this->formYear = $this->year;
        $this->formMonth = $this->month;
        $this->showForm = true;
    }

    public function openEdit(int $id): void
    {
        $transaction = FinancialTransaction::findOrFail($id);

        $this->formId = $transaction->id;
        $this->formType = $transaction->type->value;
        $this->formCategoryId = $transaction->category_id;
        $this->formDescription = $transaction->descriptionText();
        $this->formAmount = number_format((float) $transaction->amount, 2, ',', '');
        $this->formYear = $transaction->year;
        $this->formMonth = $transaction->month;
        $this->formDueDate = $transaction->due_date?->format('Y-m-d') ?? '';
        $this->formPaymentMethod = $transaction->payment_method ?? '';
        $this->formEffectiveDate = $transaction->paid_at?->format('Y-m-d') ?? $transaction->received_at?->format('Y-m-d') ?? '';
        $this->attachmentPath = $transaction->attachment_path;
        $this->attachment = null;
        $this->showForm = true;
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function saveForm(): void
    {
        $this->validate();

        $amount = $this->parseAmount($this->formAmount);

        if ($amount < 0) {
            $this->dispatch('swal:error', [
                'title' => 'Valor inválido',
                'text' => 'O valor deve ser maior ou igual a zero.',
            ]);
            return;
        }

        $category = FinancialCategory::findOrFail($this->formCategoryId);

        if ($category->type->value !== $this->formType) {
            $this->dispatch('swal:error', [
                'title' => 'Categoria inválida',
                'text' => 'A categoria selecionada não pertence ao tipo do lançamento.',
            ]);
            return;
        }

        if ($this->formId) {
            $transaction = FinancialTransaction::findOrFail($this->formId);

            if ($transaction->expense_id || $transaction->income_id) {
                $exists = FinancialTransaction::query()
                    ->where('type', $this->formType)
                    ->where('year', $this->formYear)
                    ->where('month', $this->formMonth)
                    ->where($transaction->expense_id ? 'expense_id' : 'income_id', $transaction->expense_id ?? $transaction->income_id)
                    ->where('id', '!=', $transaction->id)
                    ->exists();

                if ($exists) {
                    $this->dispatch('swal:error', [
                        'title' => 'Conflito',
                        'text' => 'Já existe um lançamento deste item no mês selecionado.',
                    ]);
                    return;
                }
            }
        }

        if ($this->attachment) {
            if ($this->attachmentPath) {
                Storage::disk()->delete($this->attachmentPath);
            }

            $this->attachmentPath = $this->attachment->store('financial/attachments', config('filesystems.default'));
        }

        $status = FinancialStatus::PENDING;
        $paidAt = null;
        $receivedAt = null;

        if ($this->formEffectiveDate) {
            if ($this->formType === 'expense') {
                $status = FinancialStatus::PAID;
                $paidAt = $this->formEffectiveDate;
            } else {
                $status = FinancialStatus::RECEIVED;
                $receivedAt = $this->formEffectiveDate;
            }
        }

        $data = [
            'user_id' => auth()->id(),
            'type' => $this->formType,
            'category_id' => $this->formCategoryId,
            'year' => $this->formYear,
            'month' => $this->formMonth,
            'amount' => $amount,
            'status' => $status->value,
            'paid_at' => $paidAt,
            'received_at' => $receivedAt,
            'description' => $this->formDescription,
            'payment_method' => $this->formPaymentMethod ?: null,
            'due_date' => $this->formDueDate ?: null,
            'attachment_path' => $this->attachmentPath,
        ];

        if ($this->formId) {
            FinancialTransaction::findOrFail($this->formId)->update($data);
        } else {
            FinancialTransaction::create($data);
        }

        $this->showForm = false;
        $this->resetForm();

        $this->dispatch('swal', [
            'title' => 'Sucesso',
            'text' => 'Lançamento salvo com sucesso.',
            'icon' => 'success',
            'timer' => 1500,
            'showConfirmButton' => false,
        ]);
    }

    public function confirmDelete(int $id): void
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Excluir lançamento?',
            'text' => 'Essa ação não pode ser desfeita.',
            'icon' => 'warning',
            'confirmButtonText' => 'Sim, excluir',
            'cancelButtonText' => 'Cancelar',
            'confirmEvent' => 'deleteTransaction',
            'confirmParams' => [$id],
        ]);
    }

    #[On('deleteTransaction')]
    public function deleteTransaction(int $id): void
    {
        $transaction = FinancialTransaction::findOrFail($id);

        if (!$transaction->isAdHoc()) {
            $this->dispatch('swal', [
                'title' => 'Não é possível excluir',
                'text' => 'Apenas lançamentos avulsos podem ser excluídos. Use cancelar para lançamentos recorrentes.',
                'icon' => 'error',
            ]);
            return;
        }

        if ($transaction->attachment_path) {
            Storage::disk()->delete($transaction->attachment_path);
        }

        $transaction->delete();

        $this->dispatch('swal', [
            'title' => 'Excluído!',
            'text' => 'Lançamento removido com sucesso.',
            'icon' => 'success',
            'timer' => 1500,
            'showConfirmButton' => false,
        ]);
    }

    public function exportCsv(): StreamedResponse
    {
        $transactions = FinancialTransaction::query()
            ->forMonth($this->year, $this->month)
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->with(['category', 'expense', 'income'])
            ->orderBy('type')
            ->orderBy('month')
            ->get();

        $filename = "lancamentos-{$this->year}-{$this->month}.csv";

        return response()->streamDownload(function () use ($transactions) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Tipo', 'Descrição', 'Categoria', 'Mês', 'Valor', 'Status', 'Método', 'Vencimento', 'Data efetiva']);

            foreach ($transactions as $transaction) {
                fputcsv($handle, [
                    $transaction->type->label(),
                    $transaction->descriptionText(),
                    $transaction->category?->name ?? '—',
                    "{$transaction->month}/{$transaction->year}",
                    number_format((float) $transaction->amount, 2, ',', '.'),
                    $transaction->status->label(),
                    $transaction->paymentMethodLabel(),
                    $transaction->dueDate()?->format('d/m/Y') ?? '—',
                    $transaction->paid_at?->format('d/m/Y') ?? $transaction->received_at?->format('d/m/Y') ?? '—',
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function startEdit(int $id): void
    {
        $transaction = FinancialTransaction::findOrFail($id);
        $this->editingId = $transaction->id;
        $this->editingAmount = number_format((float) $transaction->amount, 2, ',', '');
    }

    public function cancelEdit(): void
    {
        $this->editingId = null;
        $this->editingAmount = '';
    }

    public function saveAmount(int $id): void
    {
        $transaction = FinancialTransaction::findOrFail($id);

        $amount = $this->parseAmount($this->editingAmount);

        if ($amount < 0) {
            $this->dispatch('swal:error', [
                'title' => 'Valor inválido',
                'text' => 'O valor deve ser maior ou igual a zero.',
            ]);
            return;
        }

        $transaction->update(['amount' => $amount]);

        $this->editingId = null;
        $this->editingAmount = '';

        $this->dispatch('swal', [
            'title' => 'Sucesso',
            'text' => 'Valor atualizado.',
            'icon' => 'success',
            'timer' => 1500,
            'showConfirmButton' => false,
        ]);
    }

    public function markPaid(int $id): void
    {
        try {
            FinancialTransaction::findOrFail($id)->markAsPaid();
            $this->dispatch('swal', [
                'title' => 'Pago!',
                'text' => 'Despesa marcada como paga.',
                'icon' => 'success',
                'timer' => 1500,
                'showConfirmButton' => false,
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal:error', ['title' => 'Erro', 'text' => $e->getMessage()]);
        }
    }

    public function undoPayment(int $id): void
    {
        try {
            FinancialTransaction::findOrFail($id)->undoPayment();
            $this->dispatch('swal', [
                'title' => 'Pagamento desfeito',
                'text' => 'A despesa voltou a ficar pendente.',
                'icon' => 'info',
                'timer' => 1500,
                'showConfirmButton' => false,
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal:error', ['title' => 'Erro', 'text' => $e->getMessage()]);
        }
    }

    public function markReceived(int $id): void
    {
        try {
            FinancialTransaction::findOrFail($id)->markAsReceived();
            $this->dispatch('swal', [
                'title' => 'Recebido!',
                'text' => 'Receita marcada como recebida.',
                'icon' => 'success',
                'timer' => 1500,
                'showConfirmButton' => false,
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal:error', ['title' => 'Erro', 'text' => $e->getMessage()]);
        }
    }

    public function undoReceipt(int $id): void
    {
        try {
            FinancialTransaction::findOrFail($id)->undoReceipt();
            $this->dispatch('swal', [
                'title' => 'Recebimento desfeito',
                'text' => 'A receita voltou a ficar pendente.',
                'icon' => 'info',
                'timer' => 1500,
                'showConfirmButton' => false,
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal:error', ['title' => 'Erro', 'text' => $e->getMessage()]);
        }
    }

    public function confirmCancel(int $id): void
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Cancelar lançamento?',
            'text' => 'O lançamento será marcado como cancelado.',
            'icon' => 'warning',
            'confirmButtonText' => 'Sim, cancelar',
            'cancelButtonText' => 'Voltar',
            'confirmEvent' => 'cancelTransaction',
            'confirmParams' => [$id],
        ]);
    }

    #[On('cancelTransaction')]
    public function cancelTransaction(int $id): void
    {
        $transaction = FinancialTransaction::findOrFail($id);

        if ($transaction->status === FinancialStatus::CANCELLED) {
            $transaction->update([
                'status' => FinancialStatus::PENDING,
                'paid_at' => null,
                'received_at' => null,
            ]);
        } else {
            $transaction->update([
                'status' => FinancialStatus::CANCELLED,
                'paid_at' => null,
                'received_at' => null,
            ]);
        }
    }

    private function resetForm(): void
    {
        $this->formId = null;
        $this->formType = 'expense';
        $this->formCategoryId = null;
        $this->formDescription = '';
        $this->formAmount = '';
        $this->formYear = $this->year;
        $this->formMonth = $this->month;
        $this->formDueDate = '';
        $this->formPaymentMethod = '';
        $this->formEffectiveDate = '';
        $this->attachment = null;
        $this->attachmentPath = null;
        $this->resetValidation();
    }

    private function parseAmount(string $value): float
    {
        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);

        return (float) $value;
    }

    public function render()
    {
        $expenses = FinancialTransaction::expenses()
            ->forMonth($this->year, $this->month)
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->with(['expense', 'category'])
            ->orderBy('expense_id')
            ->get();

        $incomes = FinancialTransaction::incomes()
            ->forMonth($this->year, $this->month)
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->with(['income', 'category'])
            ->orderBy('income_id')
            ->get();

        $expenseTotal = $expenses->filter(fn ($t) => $t->status !== FinancialStatus::CANCELLED)->sum('amount');
        $incomeTotal = $incomes->filter(fn ($t) => $t->status !== FinancialStatus::CANCELLED)->sum('amount');

        $paidExpenses = $expenses->filter(fn ($t) => $t->status === FinancialStatus::PAID)->sum('amount');
        $pendingExpenses = $expenses->filter(fn ($t) => $t->status === FinancialStatus::PENDING)->sum('amount');
        $receivedIncomes = $incomes->filter(fn ($t) => $t->status === FinancialStatus::RECEIVED)->sum('amount');
        $pendingIncomes = $incomes->filter(fn ($t) => $t->status === FinancialStatus::PENDING)->sum('amount');

        $years = FinancialTransaction::query()
            ->selectRaw('DISTINCT year')
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->push(now()->year)
            ->unique()
            ->sortDesc()
            ->values();

        $categories = FinancialCategory::query()
            ->where('active', true)
            ->orderBy('name')
            ->get();

        return view('livewire.dashboard.financial.transactions', [
            'expenses' => $expenses,
            'incomes' => $incomes,
            'expenseTotal' => $expenseTotal,
            'incomeTotal' => $incomeTotal,
            'paidExpenses' => $paidExpenses,
            'pendingExpenses' => $pendingExpenses,
            'receivedIncomes' => $receivedIncomes,
            'pendingIncomes' => $pendingIncomes,
            'years' => $years,
            'months' => $this->months(),
            'categories' => $categories,
        ])->with('title', 'Lançamentos Financeiros');
    }

    private function months(): array
    {
        return [
            1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
            5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
            9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro',
        ];
    }
}