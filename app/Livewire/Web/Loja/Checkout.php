<?php

namespace App\Livewire\Web\Loja;

use App\Models\Pedido;
use App\Models\Produto;
use App\Models\ProdutoPlano;
use App\Services\PagHiperService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('web.layouts.app')]
class Checkout extends Component
{
    public ?Produto $produtoSelecionado = null;

    public ?ProdutoPlano $planoSelecionado = null;

    public string $nome = '';
    public string $email = '';
    public string $telefone = '';
    public string $cpfCnpj = '';
    public string $metodoPagamento = 'pix';
    public string $observacoes = '';

    public ?Pedido $pedido = null;

    public function mount(string $produto, int $plano): void
    {
        $this->produtoSelecionado = Produto::query()
            ->ativo()
            ->where('slug', $produto)
            ->firstOrFail();

        $this->planoSelecionado = ProdutoPlano::query()
            ->where('produto_id', $this->produtoSelecionado->id)
            ->ativo()
            ->findOrFail($plano);
    }

    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefone' => 'required|string|max:20',
            'cpfCnpj' => 'required|string|min:11|max:20',
            'metodoPagamento' => 'required|in:pix,boleto',
            'observacoes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'Informe seu nome completo.',
            'email.required' => 'Informe seu e-mail.',
            'email.email' => 'Informe um e-mail válido.',
            'telefone.required' => 'Informe um telefone para contato.',
            'cpfCnpj.required' => 'Informe seu CPF ou CNPJ.',
            'cpfCnpj.min' => 'O CPF/CNPJ deve ter pelo menos 11 dígitos.',
            'metodoPagamento.required' => 'Escolha a forma de pagamento.',
        ];
    }

    public function finalizar(): void
    {
        if ($this->pedido) {
            return;
        }

        $this->validate();

        $documento = preg_replace('/\D/', '', $this->cpfCnpj);

        if (strlen($documento) < 11) {
            $this->addError('cpfCnpj', 'Informe um CPF ou CNPJ válido.');

            return;
        }

        $pedido = DB::transaction(function () use ($documento) {
            $pedido = Pedido::create([
                'nome' => $this->nome,
                'email' => $this->email,
                'telefone' => $this->telefone,
                'cpf_cnpj' => $documento,
                'subtotal' => $this->planoSelecionado->preco,
                'desconto' => 0,
                'total' => $this->planoSelecionado->preco,
                'metodo_pagamento' => $this->metodoPagamento,
                'status_pagamento' => 'pendente',
                'status' => 'pendente',
                'observacoes' => $this->observacoes ?: null,
            ]);

            $pedido->itens()->create([
                'produto_id' => $this->produtoSelecionado->id,
                'plano_id' => $this->planoSelecionado->id,
                'produto_nome' => $this->produtoSelecionado->nome,
                'plano_nome' => $this->planoSelecionado->nome,
                'preco' => $this->planoSelecionado->preco,
            ]);

            return $pedido;
        });

        try {
            $charge = app(PagHiperService::class)->createPedidoCharge($pedido);

            $pedido->update([
                'gateway_id' => $charge['transaction_id'] ?? null,
                'payment_url' => data_get($charge, 'bank_slip.url_slip')
                    ?? data_get($charge, 'bank_slip.url_slip_pdf')
                    ?? data_get($charge, 'pix.url')
                    ?? null,
                'gateway_response' => $charge,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Falha ao gerar cobrança do pedido', [
                'pedido_id' => $pedido->id,
                'error' => $e->getMessage(),
            ]);

            $this->pedido = $pedido;

            $this->dispatch('swal', [
                'title' => 'Pedido registrado',
                'text' => 'Não foi possível gerar a cobrança automática. Nossa equipe enviará o link de pagamento para o seu e-mail.',
                'icon' => 'info',
            ]);

            return;
        }

        $this->pedido = $pedido->fresh();

        $this->dispatch('swal', [
            'title' => 'Pedido criado',
            'text' => 'Pedido ' . $pedido->codigo . ' registrado com sucesso.',
            'icon' => 'success',
            'timer' => 3000,
            'showConfirmButton' => false,
        ]);
    }

    public function render()
    {
        return view('livewire.web.loja.checkout')
            ->with('title', 'Checkout — ' . $this->produtoSelecionado?->nome);
    }
}
