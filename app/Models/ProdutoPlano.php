<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutoPlano extends Model
{
    use HasFactory;

    protected $table = 'produto_planos';
 
    protected $fillable = [
        'produto_id',
        'nome',
        'descricao',
        'preco',
        'preco_de',
        'incluso',
        'destaque',
        'status',
        'ordem',
    ];
 
    protected $casts = [
        'preco'    => 'float',
        'preco_de' => 'float',
        'incluso'  => 'array',
        'destaque' => 'boolean',
        'status'   => 'boolean',
        'ordem'    => 'integer',
    ];
 
    /**
     * Relationships
     */
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
 
    public function pedidoItens()
    {
        return $this->hasMany(PedidoItem::class, 'plano_id');
    }
 
    /**
     * Scopes
     */
    public function scopeAtivo($query)
    {
        return $query->where('status', true);
    }
 
    public function scopeDestaque($query)
    {
        return $query->where('destaque', true);
    }
 
    /**
     * Accessors
     */
    public function getTemDescontoAttribute(): bool
    {
        return !empty($this->preco_de) && $this->preco_de > $this->preco;
    }
 
    public function getPercentualDescontoAttribute(): int
    {
        if (!$this->tem_desconto) return 0;
 
        return (int) round((($this->preco_de - $this->preco) / $this->preco_de) * 100);
    }
 
    public function getPrecoFormatadoAttribute(): string
    {
        return 'R$ ' . number_format($this->preco, 2, ',', '.');
    }
 
    public function getPrecoDeFormatadoAttribute(): ?string
    {
        if (!$this->preco_de) return null;
 
        return 'R$ ' . number_format($this->preco_de, 2, ',', '.');
    }
}
