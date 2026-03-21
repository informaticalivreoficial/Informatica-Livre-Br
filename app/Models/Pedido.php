<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

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
        'gateway_response',
        'pago_em',
        'status',
        'observacoes',
    ];
 
    protected $casts = [
        'subtotal'         => 'float',
        'desconto'         => 'float',
        'total'            => 'float',
        'gateway_response' => 'array',
        'pago_em'          => 'datetime',
    ];
 
    /**
     * Relationships
     */
    public function itens()
    {
        return $this->hasMany(PedidoItem::class, 'pedido_id');
    }
 
    public function licencas()
    {
        return $this->hasMany(Licenca::class, 'pedido_id');
    }
 
    /**
     * Scopes
     */
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
 
    /**
     * Booted
     */
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
 
    /**
     * Accessors
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pendente'    => 'Pendente',
            'confirmado'  => 'Confirmado',
            'cancelado'   => 'Cancelado',
            'reembolsado' => 'Reembolsado',
            default       => 'Desconhecido',
        };
    }
 
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pendente'    => 'warning',
            'confirmado'  => 'success',
            'cancelado'   => 'danger',
            'reembolsado' => 'info',
            default       => 'secondary',
        };
    }
 
    public function getStatusPagamentoLabelAttribute(): string
    {
        return match($this->status_pagamento) {
            'pendente'  => 'Aguardando pagamento',
            'pago'      => 'Pago',
            'cancelado' => 'Cancelado',
            'estornado' => 'Estornado',
            default     => 'Desconhecido',
        };
    }
 
    public function getTotalFormatadoAttribute(): string
    {
        return 'R$ ' . number_format($this->total, 2, ',', '.');
    }
}
