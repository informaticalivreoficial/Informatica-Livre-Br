<?php

namespace App\Livewire\Dashboard\Slides;

use App\Models\Slide;
use Livewire\Component;
use Livewire\WithPagination;

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
    public bool $active = false;
    public ?int $delete_id = null; 

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

    public function toggleStatus($id)
    {              
        $slide = Slide::find($id);
        $slide->status = !$this->active;        
        $slide->save();
        $this->active = $slide->status;
    }
}
