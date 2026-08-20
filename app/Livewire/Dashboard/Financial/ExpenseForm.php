<?php

namespace App\Livewire\Dashboard\Financial;

use App\Models\FinancialCategory;
use App\Models\FinancialExpense;
use Livewire\Component;

class ExpenseForm extends Component
{
    public ?FinancialExpense $expense = null;

    public string $description = '';
    public ?int $category_id = null;
    public int $due_day = 1;
    public ?string $notes = null;
    public bool $active = true;

    public function rules()
    {
        return [
            'description' => 'required|string|min:2|max:150',
            'category_id' => 'required|exists:financial_categories,id',
            'due_day' => 'required|integer|between:1,31',
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
                'notes' => $this->expense->notes,
                'active' => $this->expense->active,
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
            'due_day' => $this->due_day,
            'notes' => $this->notes,
            'active' => $this->active,
        ];

        FinancialExpense::updateOrCreate(
            ['id' => $this->expense?->id],
            $data
        );

        $this->dispatch('swal', [
            'title' => 'Sucesso',
            'text' => 'Despesa salva com sucesso.',
            'icon' => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);

        return redirect()->route('financial.expenses');
    }

    public function render()
    {
        $title = $this->expense?->exists ? 'Editar Despesa - ' . $this->expense->description : 'Cadastrar Despesa';

        return view('livewire.dashboard.financial.expense-form', [
            'categories' => FinancialCategory::where('type', 'expense')->orderBy('name')->get(),
        ])->with('title', $title);
    }
}