<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoItem extends Model
{
    use HasFactory;

    protected $table = 'pedido_items';
 
    protected $fillable = [
        'pedido_id',
        'produto_id',
        'plano_id',
        'produto_nome',
        'plano_nome',
        'preco',
    ];
 
    protected $casts = [
        'preco' => 'float',
    ];
 
    /**
     * Relationships
     */
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }
 
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
 
    public function plano()
    {
        return $this->belongsTo(ProdutoPlano::class, 'plano_id');
    }
 
    public function licenca()
    {
        return $this->hasOne(Licenca::class, 'pedido_item_id');
    }
 
    /**
     * Accessors
     */
    public function getPrecoFormatadoAttribute(): string
    {
        return 'R$ ' . number_format($this->preco, 2, ',', '.');
    }
}
