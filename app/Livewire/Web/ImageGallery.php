<?php

namespace App\Livewire\Web;

use Livewire\Component;
use Livewire\Attributes\On;

class ImageGallery extends Component
{
    public $images = [];
    public bool $open = false;
    public int $current = 0;    

    #[On('open-gallery')]
    public function openGallery(int $index = 0): void
    {
        $this->current = $index;
        $this->open = true;
    }

    public function close(): void
    {
        $this->open = false;
    }

    public function prev(): void
    {
        $this->current = $this->current > 0
            ? $this->current - 1
            : count($this->images) - 1;
    }

    public function next(): void
    {
        $this->current = $this->current < count($this->images) - 1
            ? $this->current + 1
            : 0;
    }
    public function render()
    {
        return view('livewire.web.image-gallery');
    }
}
