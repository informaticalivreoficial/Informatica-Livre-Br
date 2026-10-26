<?php

namespace App\Console\Commands;

use App\Models\Pedido;
use App\Services\LojaNotificacaoService;
use App\Services\PagBankService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CobrarRecorrenciasLoja extends Command
{
    protected $signature = 'loja:cobrar-recorrencias {--dry-run : Apenas lista os pedidos que seriam cobrados}';

    protected $description = 'Gera cobranças dos planos recorrentes da loja';

    public function handle(PagBankService $pagbank, LojaNotificacaoService $notificacoes): int
    {
        $pedidos = Pedido::query()
            ->recorrentesAtivos()
            ->where('status_pagamento', 'pago')
            ->whereNotNull('proximo_vencimento')
            ->whereDate('proximo_vencimento', '<=', today())
            ->with('itens')
            ->get();

        $this->info("Encontrados {$pedidos->count()} pedidos recorrentes para cobrança.");

        if ($this->option('dry-run')) {
            foreach ($pedidos as $pedido) {
                $this->line("Pedido {$pedido->codigo} — vencimento {$pedido->proximo_vencimento->format('d/m/Y')}");
            }

            return self::SUCCESS;
        }

        foreach ($pedidos as $pedido) {
            try {
                $cobranca = $this->gerarCobranca($pedido);

                try {
                    $pagbank->createPedidoCheckout($cobranca);
                } catch (\Throwable $e) {
                    Log::warning('Falha ao gerar checkout da renovação da loja', [
                        'pedido_id' => $cobranca->id,
                        'pedido_pai_id' => $pedido->id,
                        'error' => $e->getMessage(),
                    ]);
                }

                $notificacoes->pedidoRecebido($cobranca->fresh());

                $this->line("Cobrança {$cobranca->codigo} gerada para o pedido {$pedido->codigo}.");
            } catch (\Throwable $e) {
                Log::error('Erro ao cobrar recorrência da loja', [
                    'pedido_id' => $pedido->id,
                    'error' => $e->getMessage(),
                ]);

                $this->error("Erro no pedido {$pedido->codigo}: {$e->getMessage()}");
            }
        }

        return self::SUCCESS;
    }

    private function gerarCobranca(Pedido $pedido): Pedido
    {
        return DB::transaction(function () use ($pedido) {
            $pedido = Pedido::query()->lockForUpdate()->findOrFail($pedido->id);

            $cobranca = Pedido::create([
                'company_id' => $pedido->company_id,
                'nome' => $pedido->nome,
                'email' => $pedido->email,
                'telefone' => $pedido->telefone,
                'cpf_cnpj' => $pedido->cpf_cnpj,
                'subtotal' => $pedido->itens->sum(fn ($item) => (float) $item->preco),
                'desconto' => 0,
                'total' => $pedido->itens->sum(fn ($item) => (float) $item->preco),
                'metodo_pagamento' => $pedido->metodo_pagamento,
                'status_pagamento' => 'pendente',
                'status' => 'pendente',
                'pedido_pai_id' => $pedido->id,
                'ciclo' => $pedido->ciclo + 1,
                'observacoes' => 'Renovação automática do pedido ' . $pedido->codigo,
            ]);

            foreach ($pedido->itens as $item) {
                $cobranca->itens()->create([
                    'produto_id' => $item->produto_id,
                    'plano_id' => $item->plano_id,
                    'produto_nome' => $item->produto_nome,
                    'plano_nome' => $item->plano_nome,
                    'preco' => $item->preco,
                ]);
            }

            $pedido->agendarProximoVencimento();

            return $cobranca;
        });
    }
}
