<?php

namespace App\Livewire\Web\Loja;

use App\Models\Produto;
use Livewire\Component;

class SelecionarPlano extends Component
{
    public Produto $produto;
    public ?int $planoSelecionado = null;
 
    public function mount(Produto $produto): void
    {
        $this->produto = $produto;
 
        // Pré-seleciona o plano destaque ou o primeiro
        $destaque = $produto->planos->where('status', true)->where('destaque', true)->first();
        $this->planoSelecionado = $destaque?->id ?? $produto->planos->where('status', true)->first()?->id;
    }
 
    public function selecionar(int $planoId): void
    {
        $this->planoSelecionado = $planoId;
    }
 
    public function irParaCheckout(): void
    {
        if (!$this->planoSelecionado) return;
 
        $this->redirect(route('web.checkout', [
            'produto' => $this->produto->slug,
            'plano'   => $this->planoSelecionado,
        ]));
    }

    public function render()
    {
        return view('livewire.web.loja.selecionar-plano');
    }
}
