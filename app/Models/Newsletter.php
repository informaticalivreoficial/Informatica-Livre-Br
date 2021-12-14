<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    use HasFactory;

    protected $table = 'newsletter'; 
    
    protected $fillable = [
        'email',
        'nome',
        'sobrenome',
        'content',
        'status',
        'autorizacao',
        'categoria',
        'whatsapp',
        'count'
    ];
    
    /**
     * Scopes
     */

    public function scopeNewsletteron($query)
    {
        return $query->where('status', 1);
    }
    
    public function scopeNewsletteroff($query)
    {
        return $query->where('status', 0);
    }
    
    /**
     * Relacionamentos
     */

    public function categoriaObject()
    {
        return $this->hasOne(NewsletterCat::class, 'id', 'categoria');
    }
    
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = ($value == '1' ? 1 : 0);
    }    
}
