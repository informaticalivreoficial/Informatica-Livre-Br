<?php

namespace App\Livewire\Web\Loja;

use App\Enums\BillingInterval;
use App\Enums\RecorrenciaStatus;
use App\Models\Company;
use App\Models\Pedido;
use App\Models\Produto;
use App\Models\ProdutoPlano;
use App\Services\LojaNotificacaoService;
use App\Services\PagBankService;
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
    public string $metodoPagamento = 'cartao_credito';
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
            'nome' => ['required', 'string', 'max:255', 'regex:/^\S+\s+.*\S$/'],
            'email' => 'required|email|max:255',
            'telefone' => 'required|string|max:20',
            'cpfCnpj' => 'required|string|min:11|max:20',
            'metodoPagamento' => 'required|in:cartao_credito,pix,boleto',
            'observacoes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'Informe seu nome completo.',
            'nome.regex' => 'Informe nome e sobrenome.',
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
            $recorrente = $this->planoSelecionado->isRecurring();
            $intervalo = $this->planoSelecionado->billing_interval;

            if ($recorrente && ! $intervalo) {
                $intervalo = BillingInterval::Monthly;
            }

            $company = Company::query()
                ->where(function ($query) {
                    $query->where('email', $this->email)
                        ->orWhere('responsable_email', $this->email);
                })
                ->first();

            $pedido = Pedido::create([
                'company_id' => $company?->id,
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
                'recorrente' => $recorrente,
                'billing_interval' => $recorrente ? $intervalo?->value : null,
                'recorrencia_status' => $recorrente ? RecorrenciaStatus::Ativa->value : null,
                'proximo_vencimento' => $recorrente && $intervalo
                    ? today()->addMonths($intervalo->months())
                    : null,
                'ciclo' => 1,
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

        $this->pedido = $pedido;

        $notificacoes = app(LojaNotificacaoService::class);

        try {
            $checkout = app(PagBankService::class)->createPedidoCheckout($pedido);
        } catch (\Throwable $e) {
            Log::warning('Falha ao gerar checkout do PagBank', [
                'pedido_id' => $pedido->id,
                'error' => $e->getMessage(),
            ]);

            $notificacoes->pedidoRecebido($pedido);

            $this->dispatch('swal', [
                'title' => 'Pedido registrado',
                'text' => 'Não foi possível abrir o pagamento automático. Nossa equipe enviará o link de cobrança para o seu e-mail.',
                'icon' => 'info',
            ]);

            return;
        }

        $notificacoes->pedidoRecebido($pedido->fresh());

        $this->redirect($checkout['url']);
    }

    public function render()
    {
        return view('livewire.web.loja.checkout')
            ->with('title', 'Checkout — ' . $this->produtoSelecionado?->nome);
    }
}
