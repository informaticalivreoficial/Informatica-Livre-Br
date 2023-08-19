<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    use HasFactory;

    protected $table = 'servicos';

    protected $fillable = [
        'categoria',
        'name',
        'content',
        'headline',
        'slug',
        'tags',
        'views',
        'cat_pai',        
        'comentarios',        
        'status',
        'exibir',
        'ativacao',
        'tipo_pagamento',
        'thumb_legenda',
        'publish_at',
        'exibivalores',        
        'valor',
        'valor_mensal',
        'valor_trimestral',
        'valor_semestral',
        'valor_anual',
        'valor_bianual'
    ];

    /**
     * Scopes
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 1);
    }

    public function scopeUnavailable($query)
    {
        return $query->where('status', 0);
    }

    public function scopeExibir($query)
    {
        return $query->where('exibir', 1);
    }

    public function images()
    {
        return $this->hasMany(GbServico::class, 'servico', 'id')->orderBy('cover', 'ASC');
    }
    
    public function countimages()
    {
        return $this->hasMany(GbServico::class, 'servico', 'id')->count();
    }
}
