<?php

namespace App\Livewire\Dashboard\Slides;

use App\Models\Slide;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Slides extends Component
{
    use WithPagination;

    // Quantidade de itens por página
    public int $perPage = 25;
    protected $paginationTheme = 'bootstrap';
    public string $search = '';
    protected $updatesQueryString = ['search'];
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    #{Url}
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'desc' ? 'asc' : 'desc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'desc';
        }

        $this->resetPage();
    }    

    public function toggleStatus($id)
    {              
        $slide = Slide::findOrFail($id);
        $slide->status = !$slide->status;        
        $slide->save();
    }

    public function setDeleteId($id)
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Excluir Banner Slide?',
            'text' => 'Essa ação não pode ser desfeita.',
            'icon' => 'warning',
            'confirmButtonText' => 'Sim, excluir',
            'cancelButtonText' => 'Cancelar',
            'confirmEvent' => 'deleteBanner',
            'confirmParams' => [$id],
        ]);       
    }

    #[On('deleteBanner')]
    public function deleteBanner($id): void
    {
        $slide = Slide::findOrFail($id);

        $logoPath = $slide->image;
        if ($logoPath && Storage::disk('public')->exists($logoPath)) {
            Storage::disk('public')->delete($logoPath);
        }

        $slide->delete();

        $this->dispatch('swal', [
            'title' => 'Excluído!',
            'text'  => 'Banner Slide excluído com sucesso!',
            'icon'  => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);                
    }  

    public function render()
    {
        $title = 'Banners - Slides';
        $searchableFields = ['title'];
        $slides = Slide::query()
            ->when($this->search, function ($query) use ($searchableFields) {
                $query->where(function ($q) use ($searchableFields) {
                    foreach ($searchableFields as $field) {
                        $q->orWhere($field, 'LIKE', "%{$this->search}%");
                    }
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
        return view('livewire.dashboard.slides.slides',[
            'title' => $title,
            'slides' => $slides
        ]);
    }
}
