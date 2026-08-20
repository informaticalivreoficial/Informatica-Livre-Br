<?php

namespace App\Livewire\Dashboard\Financial;

use App\Enums\FinancialStatus;
use App\Models\FinancialCategory;
use App\Models\FinancialIncome;
use Livewire\Component;

class IncomeForm extends Component
{
    public ?FinancialIncome $income = null;

    public string $description = '';
    public ?int $category_id = null;
    public int $receive_day = 1;
    public string $amount = '0,00';
    public ?string $notes = null;
    public bool $active = true;

    public function rules()
    {
        return [
            'description' => 'required|string|min:2|max:150',
            'category_id' => 'required|exists:financial_categories,id',
            'receive_day' => 'required|integer|between:1,31',
            'amount' => ['required', 'regex:/^\d{1,3}(\.\d{3})*(,\d{1,2})?$|^\d+(,\d{1,2})?$/'],
            'notes' => 'nullable|string|max:500',
            'active' => 'boolean',
        ];
    }

    public function mount()
    {
        if ($this->income) {
            $this->fill([
                'description' => $this->income->description,
                'category_id' => $this->income->category_id,
                'receive_day' => $this->income->receive_day,
                'amount' => number_format((float) $this->income->amount, 2, ',', ''),
                'notes' => $this->income->notes,
                'active' => $this->income->active,
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
            'receive_day' => $this->receive_day,
            'amount' => $amount,
            'notes' => $this->notes,
            'active' => $this->active,
        ];

        $income = FinancialIncome::updateOrCreate(
            ['id' => $this->income?->id],
            $data
        );

        $income->transactions()
            ->where('status', FinancialStatus::PENDING->value)
            ->where('amount', 0)
            ->update(['amount' => $amount]);

        $this->dispatch('swal', [
            'title' => 'Sucesso',
            'text' => 'Receita salva com sucesso.',
            'icon' => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);

        return redirect()->route('financial.incomes');
    }

    private function parseAmount(string $value): float
    {
        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);

        return (float) $value;
    }

    public function render()
    {
        $title = $this->income?->exists ? 'Editar Receita - ' . $this->income->description : 'Cadastrar Receita';

        return view('livewire.dashboard.financial.income-form', [
            'categories' => FinancialCategory::where('type', 'income')->orderBy('name')->get(),
        ])->with('title', $title);
    }
}