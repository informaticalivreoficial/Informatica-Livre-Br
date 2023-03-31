<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    use HasFactory;

    protected $table = 'bancos';

    protected $fillable = [
        'bank_account_id', 'bank_code', 'bank_name', 'account_type', 'agencia', 'conta',
    ];

    /**
     * Scopes
    */

    /**
     * Relacionamentos
    */

    /**
     * Accerssors and Mutators
    */
    public function getLogo() {
        if($this->bank_code == '033'){
            return url(asset('backend/assets/images/bancos/santander.png'));
        }elseif($this->bank_code == '290'){
            return url(asset('backend/assets/images/bancos/pagbank.jpg'));
        }elseif($this->bank_code == '001'){
            return url(asset('backend/assets/images/bancos/banco-do-brasil-logo.png'));
        }else{
            url(asset('backend/assets/images/image.jpg')); 
        }
    }
}
