<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Cofre extends Model
{
    use HasFactory;

    protected $table = 'cofre';

    protected $fillable = [
        'name', 'email', 'logomarca', 'login', 'password', 'token', 'content', 'status',
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

    /**
     * Accerssors and Mutators
    */
    public function cover()
    {       
        if(empty($this->logomarca) || !Storage::disk()->exists($this->logomarca)) {
            return url(asset('backend/assets/images/image.jpg'));
        }

        return Storage::url($this->logomarca);
    }

}
