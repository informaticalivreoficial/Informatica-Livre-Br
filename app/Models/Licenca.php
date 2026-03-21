<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Licenca extends Model
{
    use HasFactory;

    protected $table = 'licencas';
 
    protected $fillable = [
        'pedido_id',
        'pedido_item_id',
        'produto_id',
        'nome',
        'email',
        'chave',
        'url_sistema',
        'url_painel',
        'credenciais',
        'status',
        'ativada_em',
        'notas',
    ];
 
    protected $casts = [
        'ativada_em' => 'datetime',
    ];
 
    /**
     * Relationships
     */
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }
 
    public function pedidoItem()
    {
        return $this->belongsTo(PedidoItem::class, 'pedido_item_id');
    }
 
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
 
    /**
     * Booted
     */
    protected static function booted(): void
    {
        static::creating(function (Licenca $licenca) {
            $licenca->chave = $licenca->chave ?? strtoupper(Str::random(6) . '-' . Str::random(6) . '-' . Str::random(6));
        });
 
        static::updating(function (Licenca $licenca) {
            if ($licenca->isDirty('status') && $licenca->status === 'ativa') {
                $licenca->ativada_em = now();
            }
        });
    }
 
    /**
     * Scopes
     */
    public function scopeAtiva($query)
    {
        return $query->where('status', 'ativa');
    }
 
    public function scopeAguardando($query)
    {
        return $query->where('status', 'aguardando');
    }
 
    /**
     * Accessors
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'aguardando' => 'Aguardando instalação',
            'ativa'      => 'Ativa',
            'suspensa'   => 'Suspensa',
            'cancelada'  => 'Cancelada',
            default      => 'Desconhecido',
        };
    }
 
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'aguardando' => 'warning',
            'ativa'      => 'success',
            'suspensa'   => 'info',
            'cancelada'  => 'danger',
            default      => 'secondary',
        };
    }
}
