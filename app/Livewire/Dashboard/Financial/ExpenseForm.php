<?php

namespace App\Livewire\Dashboard\Financial;

use App\Enums\FinancialStatus;
use App\Models\FinancialCategory;
use App\Models\FinancialExpense;
use Livewire\Component;

class ExpenseForm extends Component
{
    public ?FinancialExpense $expense = null;

    public string $description = '';
    public ?int $category_id = null;
    public int $due_day = 1;
    public string $amount = '0,00';
    public ?string $notes = null;
    public bool $active = true;

    public function rules()
    {
        return [
            'description' => 'required|string|min:2|max:150',
            'category_id' => 'required|exists:financial_categories,id',
            'due_day' => 'required|integer|between:1,31',
            'amount' => ['required', 'regex:/^\d{1,3}(\.\d{3})*(,\d{1,2})?$|^\d+(,\d{1,2})?$/'],
            'notes' => 'nullable|string|max:500',
            'active' => 'boolean',
        ];
    }

    public function mount()
    {
        if ($this->expense) {
            $this->fill([
                'description' => $this->expense->description,
                'category_id' => $this->expense->category_id,
                'due_day' => $this->expense->due_day,
                'amount' => number_format((float) $this->expense->amount, 2, ',', ''),
                'notes' => $this->expense->notes,
                'active' => $this->expense->active,
            ]);
        }
    }

    public function save()
    {
        $this->validate();

        $amount = $this->parseAmount($this->amount);

        $data = [
            'user_id' => auth()->id(),
            'description' => $this->description,
            'category_id' => $this->category_id,
            'due_day' => $this->due_day,
            'amount' => $amount,
            'notes' => $this->notes,
            'active' => $this->active,
        ];

        $expense = FinancialExpense::updateOrCreate(
            ['id' => $this->expense?->id],
            $data
        );

        $expense->transactions()
            ->where('status', FinancialStatus::PENDING->value)
            ->where('amount', 0)
            ->update(['amount' => $amount]);

        $this->dispatch('swal', [
            'title' => 'Sucesso',
            'text' => 'Despesa salva com sucesso.',
            'icon' => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);

        return redirect()->route('financial.expenses');
    }

    private function parseAmount(string $value): float
    {
        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);

        return (float) $value;
    }

    public function render()
    {
        $title = $this->expense?->exists ? 'Editar Despesa - ' . $this->expense->description : 'Cadastrar Despesa';

        return view('livewire.dashboard.financial.expense-form', [
            'categories' => FinancialCategory::where('type', 'expense')->orderBy('name')->get(),
        ])->with('title', $title);
    }
}