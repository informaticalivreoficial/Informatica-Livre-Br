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
    public function scopeAvailable($query)
    {
        return $query->where('status', 1);
    }

    public function scopeUnavailable($query)
    {
        return $query->where('status', 0);
    }
    
    /**
     * Relacionamentos
    */  
    public function newsletterCat()
    {
        return $this->belongsTo(NewsletterCat::class, 'categoria', 'id');
    }

    /**
     * Accerssors and Mutators
    */
    public function getCreatedAtAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        return date('d/m/Y', strtotime($value));
    }
    
    public function getAutorizacaoAttribute($value)
    {
        if(empty($value)){
            return null;
        }

        return ($value == '1' ? '<span class="badge bg-success">Sim</span>' : '<span class="badge bg-danger">Não</span>');
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = ($value == '1' ? 1 : 0);
    }    
}
