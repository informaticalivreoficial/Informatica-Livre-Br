<?php

namespace App\Models;

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
        'observacoes',
    ];

    protected $casts = [
        'subtotal'         => 'decimal:2',
        'desconto'         => 'decimal:2',
        'total'            => 'decimal:2',
        'gateway_response' => 'array',
        'pago_em'          => 'datetime',
    ];

    public function itens()
    {
        return $this->hasMany(PedidoItem::class, 'pedido_id');
    }

    public function licencas()
    {
        return $this->hasMany(Licenca::class, 'pedido_id');
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

        $this->gerarLicencas();
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

    public function getTotalFormatadoAttribute(): string
    {
        return 'R$ ' . number_format((float) $this->total, 2, ',', '.');
    }
}
