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
 
    /**
     * Relationships
     */
    public function planos()
    {
        return $this->hasMany(ProdutoPlano::class, 'produto_id')->orderBy('ordem');
    }
 
    public function licencas()
    {
        return $this->hasMany(Licenca::class, 'produto_id');
    }

    public function images()
    {
        return $this->hasMany(ProdutoGB::class, 'produto');
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
    public function getMenorPrecoAttribute(): ?float
    {
        return $this->planos->where('status', true)->min('preco');
    }
}
