<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;

class Safe extends Model
{
    use HasFactory;

    protected $table = 'safes';

    protected $fillable = [
        'title',
        'email',
        'logo',
        'login',
        'link',
        'password',
        'token',
        'status',
        'content'
    ];

    /**
     * Accerssors and Mutators
    */ 
    public function getlogo()
    {
        if(empty($this->logo) || !Storage::disk()->exists($this->logo)) {
            return asset('theme/images/image.jpg');
        } 
        return Storage::url($this->logo);
    }

    public function setPasswordAttribute($value)
    {
        if ($value !== null && $value !== '') {
            $this->attributes['password'] = Crypt::encryptString($value);
        }
    }

    public function getPasswordAttribute($value)
    {
        if (! $value) {
            return null;
        }

        try {
            return Crypt::decryptString($value);
        } catch (DecryptException $e) {
            // Registro antigo sem criptografia
            return $value;
        }
    }

    public function setTokenAttribute($value)
    {
        if ($value !== null && $value !== '') {
            $this->attributes['token'] = Crypt::encryptString($value);
        }
    }

    public function getTokenAttribute($value)
    {
        return $this->decryptValue($value);
    }

    public function setContentAttribute($value)
    {
        if ($value !== null && $value !== '') {
            $this->attributes['content'] = Crypt::encryptString($value);
        }
    }

    public function getContentAttribute($value)
    {
        return $this->decryptValue($value);
    }

    protected function decryptValue($value)
    {
        if (! $value) {
            return null;
        }

        try {
            return Crypt::decryptString($value);
        } catch (DecryptException $e) {
            // fallback para dados antigos sem criptografia
            return $value;
        }
    }
}
