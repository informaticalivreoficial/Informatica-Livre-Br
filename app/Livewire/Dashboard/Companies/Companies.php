<?php

namespace App\Livewire\Dashboard\Companies;

use App\Models\Company;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Companies extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $search = '';

    public string $sortField = 'social_name';

    public string $sortDirection = 'asc';

    public ?int $delete_id = null;

    public $showCompanyModal = false;
    public $companySelected;

    public function viewCompany($id)
    {
        $this->companySelected = Company::with(['users', 'ocorrencias'])->find($id);
        $this->showCompanyModal = true;
    }

    #{Url}
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function render()
    {
        $title = 'Lista de Empresas';
        $companies = Company::query()->when($this->search, function($query){
            $query->orWhere('social_name', 'LIKE', "%{$this->search}%");
            $query->orWhere('email', 'LIKE', "%{$this->search}%");
        })->orderBy($this->sortField, $this->sortDirection)->paginate(35);
        return view('livewire.dashboard.companies.companies',[
            'companies' => $companies
        ])->with('title', $title);
    }

    public function toggleStatus($id)
    {              
        $company = Company::findOrFail($id);
        $company->status = !$company->status;        
        $company->save();
    }

    public function setDeleteId($id)
    {
        $this->delete_id = $id;
        $this->dispatch('delete-prompt');        
    }

    #[On('goOn-Delete')]
    public function delete(): void
    {
        try {
            $company = Company::findOrFail($this->delete_id);

            $logoPath = $company->logo;
            if ($logoPath && Storage::disk('public')->exists($logoPath)) {
                Storage::disk('public')->delete($logoPath);
            }

            $company->delete();

            $this->delete_id = null;

            $this->dispatch('swal', [
                'title' => 'Sucesso!',
                'icon'  => 'success',
                'text'  => 'Empresa removida!',
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'title' => 'Erro!',
                'icon'  => 'error',
                'text'  => 'Não foi possível excluir a empresa.',
            ]);
        }
    }
}
