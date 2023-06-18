<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';

    protected $fillable = [
        'empresa',
        'orcamento',
        'status',
        'valor',
        'url_slip',
        'digitable_line',
        'vencimento',
        'empresa',
        'gateway',
        'uuid'
    ];

    /**
     * Relacionamentos
    */
    public function getEmpresa()
    {
        return $this->hasOne(Empresa::class, 'id', 'empresa');
    }

    public function itens()
    {
        return $this->hasMany(ItemPedido::class, 'pedido', 'id');
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
            return '<small class="badge badge-info">Finalizado</small>'; 
        }else{
            return '<small class="badge badge-warning">Em Análise</small>'; 
        }
    }

    public function setVencimentoAttribute($value)
    {
        $this->attributes['vencimento'] = (!empty($value) ? $this->convertStringToDate($value) : null);
    }

    public function setValorAttribute($value)
    {
        $this->attributes['valor'] = (!empty($value) ? floatval($this->convertStringToDouble($value)) : null);
    }

    public function getValorAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        return number_format($value / 100, 2, ',', '');
    }

    /**
     * Scopes
    */
    public function itensTotalValor()
    {
        return $this->hasMany(ItemPedido::class, 'pedido', 'id')->sum('valor');
    }

    private function convertStringToDate(?string $param)
    {
        if (empty($param)) {
            return null;
        }
        list($day, $month, $year) = explode('/', $param);
        return (new \DateTime($year . '-' . $month . '-' . $day))->format('Y-m-d');
    }

    private function convertStringToDouble($param)
    {
        if(empty($param)){
            return null;
        }
        return str_replace(',', '.', str_replace('.', '', $param));
    }

    private function clearField(?string $param)
    {
        if (empty($param)) {
            return null;
        }
        return str_replace(['.', '-', '/', '(', ')', ' '], '', $param);
    }
}
