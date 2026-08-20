<?php

namespace App\Livewire\Dashboard\Financial;

use App\Models\FinancialCategory;
use App\Models\FinancialIncome;
use Livewire\Component;

class IncomeForm extends Component
{
    public ?FinancialIncome $income = null;

    public string $description = '';
    public ?int $category_id = null;
    public int $receive_day = 1;
    public ?string $notes = null;
    public bool $active = true;

    public function rules()
    {
        return [
            'description' => 'required|string|min:2|max:150',
            'category_id' => 'required|exists:financial_categories,id',
            'receive_day' => 'required|integer|between:1,31',
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
                'notes' => $this->income->notes,
                'active' => $this->income->active,
            ]);
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'user_id' => auth()->id(),
            'description' => $this->description,
            'category_id' => $this->category_id,
            'receive_day' => $this->receive_day,
            'notes' => $this->notes,
            'active' => $this->active,
        ];

        FinancialIncome::updateOrCreate(
            ['id' => $this->income?->id],
            $data
        );

        $this->dispatch('swal', [
            'title' => 'Sucesso',
            'text' => 'Receita salva com sucesso.',
            'icon' => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);

        return redirect()->route('financial.incomes');
    }

    public function render()
    {
        $title = $this->income?->exists ? 'Editar Receita - ' . $this->income->description : 'Cadastrar Receita';

        return view('livewire.dashboard.financial.income-form', [
            'categories' => FinancialCategory::where('type', 'income')->orderBy('name')->get(),
        ])->with('title', $title);
    }
}