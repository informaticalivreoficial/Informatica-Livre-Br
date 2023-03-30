<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPedido extends Model
{
    use HasFactory;

    protected $table = 'item_pedido';

    protected $fillable = [
        'pedido',
        'descricao',
        'valor',
        'quantidade'
    ];

    /**
     * Relacionamentos
    */
    public function pedido()
    {
        return $this->belongsTo(pedido::class, 'pedido', 'id');
    }

    /**
     * Scopes
    */
    public function setValorAttribute($value)
    {
        $this->attributes['valor'] = (!empty($value) ? floatval($this->convertStringToDouble($value)) : null);
    }

    private function convertStringToDouble($param)
    {
        if(empty($param)){
            return null;
        }
        return str_replace(',', '.', str_replace('.', '', $param));
    }
    
}
