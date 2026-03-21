<?php

namespace App\Livewire\Dashboard\Slides;

use App\Models\Slide;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class SlideForm extends Component
{
    use WithFileUploads;

    public ?Slide $slide = null;

    public $title;
    public $link;
    public $target;
    public $view_title;
    public $content;
    public $expired_at;
    public $status;
    public $image;
    public ?string $logoPath = null;

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'image' => $this->slide?->exists ? 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048' : 'required|image|mimes:jpeg,jpg,png,webp|max:2048',
        ];
    }    

    public function mount(?Slide $slide = null)
    {
        $this->slide = $slide;

        if ($slide && $slide->exists) { // 👈 adiciona exists
            $this->logoPath    = $slide->image;
            $this->title       = $slide->title;
            $this->link        = $slide->link;
            $this->content     = $slide->content;
            $this->target      = $slide->target;
            $this->view_title  = $slide->view_title;
            $this->expired_at  = $slide->expired_at;
            $this->status      = $slide->status;
        }
    }

    public function save()
    {
        $this->validate();

        if ($this->image) {
            if ($this->slide?->exists && $this->logoPath) {
                Storage::disk('public')->delete($this->logoPath);
            }

            $filename = uniqid() . '.webp';
            $path     = 'slides/' . $filename;

            $manager = new ImageManager(new Driver());
            $img     = $manager->read($this->image->getRealPath());
            $img->scaleDown(width: 1920);
            $encoded = $img->toWebp(85);

            Storage::disk('public')->put($path, $encoded);
            $this->logoPath = $path;
        }        

        $data = [
            'title' => $this->title,
            'link' => $this->link,
            'target' => $this->target,
            'view_title' => $this->view_title,
            'content' => $this->content,
            'expired_at' => $this->expired_at,
            'status' => $this->status,
            'image' => $this->logoPath
        ];

        if ($this->slide) {
            $this->slide->update($data);
            $text = 'Slide Atualizado com sucesso!';
        } else {
            $this->slide = Slide::create($data);
            $text = 'Slide Cadastrado com sucesso!';
        }

        $this->dispatch('swal', [
            'title' => 'Sucesso!',
            'text' => $text,
            'icon' => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);

        return redirect()->route('slides.edit', $this->slide);
    }

    public function render()
    {
        return view('livewire.dashboard.slides.slide-form',[
            'titlee' => $this->slide ? 'Editar Banner' : 'Cadastrar Banner',
        ]);
    }
    
}
