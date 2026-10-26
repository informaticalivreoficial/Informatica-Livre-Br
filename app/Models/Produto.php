<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Produto extends Model
{
    use HasFactory;

    protected $table = 'produtos';

    protected $fillable = [
        'nome',
        'slug',
        'headline',
        'descricao',
        'conteudo',
        'demo_url',
        'features',
        'screenshots',
        'destaque',
        'status',
        'ordem',
    ];

    protected $casts = [
        'features'    => 'array',
        'screenshots' => 'array',
        'destaque'    => 'boolean',
        'status'      => 'boolean',
        'ordem'       => 'integer',
    ];

    public function planos()
    {
        return $this->hasMany(ProdutoPlano::class, 'produto_id')->orderBy('ordem');
    }

    public function planosAtivos()
    {
        return $this->hasMany(ProdutoPlano::class, 'produto_id')
            ->where('status', true)
            ->orderBy('ordem');
    }

    public function licencas()
    {
        return $this->hasMany(Licenca::class, 'produto_id');
    }

    public function images()
    {
        return $this->hasMany(ProdutoGB::class, 'produto');
    }

    public function pedidoItens()
    {
        return $this->hasMany(PedidoItem::class, 'produto_id');
    }

    public function scopeAtivo($query)
    {
        return $query->where('status', true);
    }

    public function scopeDestaque($query)
    {
        return $query->where('destaque', true);
    }

    public function scopeOrdenado($query)
    {
        return $query->orderBy('ordem')->orderBy('nome');
    }

    public function getMenorPrecoAttribute(): ?float
    {
        $precos = $this->planos->where('status', true)->pluck('preco');

        return $precos->isEmpty() ? null : (float) $precos->min();
    }

    public function getCoverAttribute(): ?ProdutoGB
    {
        return $this->images->firstWhere('cover', true)
            ?? $this->images->sortBy('order_img')->first();
    }

    public function getCoverUrlAttribute(): string
    {
        if ($cover = $this->cover) {
            return $cover->url;
        }

        $screenshots = array_values((array) $this->screenshots);

        if (! empty($screenshots)) {
            return Storage::disk()->url($screenshots[0]);
        }

        return asset('theme/images/image.jpg');
    }

    public function getTemPlanoRecorrenteAttribute(): bool
    {
        return $this->planos->contains(fn (ProdutoPlano $plano) => $plano->isRecurring());
    }
}
