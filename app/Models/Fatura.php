<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fatura extends Model
{
    use HasFactory;

    protected $table = 'faturas';

    protected $fillable = [
        'transaction_id',
        'uudi',
        'paid_date',
        'form_sendat',
        'vencimento',
        'gateway',
        'pedido',
        'valor',
        'url_slip',
        'digitable_line',
        'status'      
    ];

    /**
     * Scopes
    */
    public function scopeApproved($query)
    {
        return $query->where('status', 'completed')->orWhere('status', 'paid');
    }

    public function scopeInprocess($query)
    {
        return $query->where('status', 'processing')->orWhere('status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'canceled');
    }

    /**
     * Relacionamentos
    */
    public function pedidoObject()
    {
        return $this->hasOne(Pedido::class, 'id', 'pedido');
    }    

    /**
     * Accerssors and Mutators
    */
    public function getStatus() {
        if($this->status == 'processing'){
            return '<small class="badge badge-warning">Em Análise</small>';
        }elseif($this->status == 'pending'){
            return '<small class="badge badge-primary">Aguardando Pagamento</small>';
        }elseif($this->status == 'canceled'){
            return '<small class="badge badge-danger">Cancelado</small>';
        }elseif($this->status == 'paid'){
            return '<small class="badge badge-success">Pago</small>';
        }elseif($this->status == 'completed'){
            return '<small class="badge badge-success">Finalizado/Pago</small>'; 
        }else{
            return '<small class="badge badge-warning">Em Análise</small>'; 
        }
    }
    
    // public function setValorAttribute($value)
    // {
    //     $this->attributes['valor'] = (!empty($value) ? floatval($this->convertStringToDouble($value)) : null);
    // }

    private function convertStringToDouble($param)
    {
        if(empty($param)){
            return null;
        }
        return str_replace(',', '.', str_replace('.', '', $param));
    }
}
