<?php

namespace App\Models;

use App\Enums\BillingInterval;
use App\Enums\RecorrenciaStatus;
use App\Services\LojaNotificacaoService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedido extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'pedidos';

    protected $fillable = [
        'codigo',
        'company_id',
        'nome',
        'email',
        'telefone',
        'cpf_cnpj',
        'subtotal',
        'desconto',
        'total',
        'metodo_pagamento',
        'status_pagamento',
        'gateway_id',
        'payment_url',
        'gateway_response',
        'pago_em',
        'status',
        'recorrente',
        'billing_interval',
        'recorrencia_status',
        'ciclo',
        'proximo_vencimento',
        'pedido_pai_id',
        'observacoes',
    ];

    protected $casts = [
        'subtotal'           => 'decimal:2',
        'desconto'           => 'decimal:2',
        'total'              => 'decimal:2',
        'gateway_response'   => 'array',
        'pago_em'            => 'datetime',
        'recorrente'         => 'boolean',
        'billing_interval'   => BillingInterval::class,
        'recorrencia_status' => RecorrenciaStatus::class,
        'ciclo'              => 'integer',
        'proximo_vencimento' => 'date',
    ];

    public function itens()
    {
        return $this->hasMany(PedidoItem::class, 'pedido_id');
    }

    public function licencas()
    {
        return $this->hasMany(Licenca::class, 'pedido_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function pai()
    {
        return $this->belongsTo(self::class, 'pedido_pai_id');
    }

    public function cobrancas()
    {
        return $this->hasMany(self::class, 'pedido_pai_id');
    }

    public function scopePendente($query)
    {
        return $query->where('status', 'pendente');
    }

    public function scopeConfirmado($query)
    {
        return $query->where('status', 'confirmado');
    }

    public function scopePago($query)
    {
        return $query->where('status_pagamento', 'pago');
    }

    public function scopeRecorrentesAtivos($query)
    {
        return $query->where('recorrente', true)
            ->where('recorrencia_status', RecorrenciaStatus::Ativa->value);
    }

    protected static function booted(): void
    {
        static::creating(function (Pedido $pedido) {
            $pedido->codigo = $pedido->codigo ?? self::gerarCodigo();
        });
    }

    private static function gerarCodigo(): string
    {
        $ano    = date('Y');
        $ultimo = self::whereYear('created_at', $ano)->count() + 1;

        return 'PED-' . $ano . '-' . str_pad($ultimo, 5, '0', STR_PAD_LEFT);
    }

    public function markAsPaid(): void
    {
        if ($this->status_pagamento === 'pago') {
            return;
        }

        $this->update([
            'status_pagamento' => 'pago',
            'status'           => 'confirmado',
            'pago_em'          => $this->pago_em ?? now(),
        ]);

        if ($this->pedido_pai_id) {
            $this->renovarLicencas();
        } else {
            $this->gerarLicencas();
        }

        app(LojaNotificacaoService::class)->pedidoPago($this);
    }

    public function markAsCanceled(): void
    {
        if ($this->status_pagamento === 'pago') {
            throw new \Exception('Um pedido pago não pode ser cancelado. Use o reembolso.');
        }

        $this->update([
            'status'           => 'cancelado',
            'status_pagamento' => 'cancelado',
        ]);

        if ($this->recorrente) {
            $this->update(['recorrencia_status' => RecorrenciaStatus::Cancelada->value]);
        }
    }

    public function markAsRefunded(): void
    {
        if ($this->status_pagamento !== 'pago') {
            throw new \Exception('Só é possível reembolsar um pedido pago.');
        }

        $this->update([
            'status'           => 'reembolsado',
            'status_pagamento' => 'estornado',
        ]);

        $this->licencas()->update(['status' => 'cancelada']);

        if ($this->recorrente) {
            $this->update(['recorrencia_status' => RecorrenciaStatus::Cancelada->value]);
        }
    }

    public function gerarLicencas(): void
    {
        foreach ($this->itens as $item) {
            Licenca::firstOrCreate(
                ['pedido_item_id' => $item->id],
                [
                    'pedido_id'  => $this->id,
                    'produto_id' => $item->produto_id,
                    'nome'       => $this->nome,
                    'email'      => $this->email,
                    'status'     => 'aguardando',
                ]
            );
        }
    }

    public function renovarLicencas(): void
    {
        $this->pai?->licencas()->update([
            'status'     => 'ativa',
            'ativada_em' => now(),
        ]);
    }

    public function isRenovacao(): bool
    {
        return ! is_null($this->pedido_pai_id);
    }

    public function pausarRecorrencia(): void
    {
        if (! $this->recorrente) {
            throw new \Exception('Este pedido não possui cobrança recorrente.');
        }

        if ($this->recorrencia_status === RecorrenciaStatus::Cancelada) {
            throw new \Exception('A recorrência deste pedido está cancelada.');
        }

        $this->update(['recorrencia_status' => RecorrenciaStatus::Pausada->value]);
    }

    public function retomarRecorrencia(): void
    {
        if (! $this->recorrente) {
            throw new \Exception('Este pedido não possui cobrança recorrente.');
        }

        if ($this->recorrencia_status === RecorrenciaStatus::Cancelada) {
            throw new \Exception('A recorrência deste pedido foi cancelada e não pode ser retomada.');
        }

        $this->update(['recorrencia_status' => RecorrenciaStatus::Ativa->value]);
    }

    public function cancelarRecorrencia(): void
    {
        if (! $this->recorrente) {
            throw new \Exception('Este pedido não possui cobrança recorrente.');
        }

        $this->update(['recorrencia_status' => RecorrenciaStatus::Cancelada->value]);
    }

    public function agendarProximoVencimento(): void
    {
        if (! $this->recorrente) {
            return;
        }

        $intervalo = $this->billing_interval ?? BillingInterval::Monthly;
        $base = $this->proximo_vencimento ?? today();

        $this->update([
            'proximo_vencimento' => $base->copy()->addMonths($intervalo->months()),
            'ciclo' => $this->ciclo + 1,
        ]);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pendente'    => 'Pendente',
            'confirmado'  => 'Confirmado',
            'cancelado'   => 'Cancelado',
            'reembolsado' => 'Reembolsado',
            default       => 'Desconhecido',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pendente'    => 'warning',
            'confirmado'  => 'success',
            'cancelado'   => 'danger',
            'reembolsado' => 'info',
            default       => 'secondary',
        };
    }

    public function getStatusPagamentoLabelAttribute(): string
    {
        return match ($this->status_pagamento) {
            'pendente'  => 'Aguardando pagamento',
            'pago'      => 'Pago',
            'cancelado' => 'Cancelado',
            'estornado' => 'Estornado',
            default     => 'Desconhecido',
        };
    }

    public function getMetodoPagamentoLabelAttribute(): string
    {
        return match ($this->metodo_pagamento) {
            'pix'            => 'PIX',
            'boleto'         => 'Boleto',
            'cartao_credito' => 'Cartão de crédito',
            default          => '—',
        };
    }

    public function getRecorrenciaLabelAttribute(): string
    {
        if (! $this->recorrente) {
            return $this->isRenovacao() ? 'Cobrança de renovação' : 'Pagamento único';
        }

        return $this->recorrencia_status?->label() ?? 'Ativa';
    }

    public function getTotalFormatadoAttribute(): string
    {
        return 'R$ ' . number_format((float) $this->total, 2, ',', '.');
    }
}
