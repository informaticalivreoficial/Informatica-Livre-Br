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
        'empresa'
    ];

    /**
     * Relacionamentos
    */
    public function getEmpresa()
    {
        return $this->hasOne(Empresa::class, 'id', 'empresa');
    }

    /**
     * Accerssors and Mutators
    */
    public function getStatus() {
        if($this->status == 5){
            return '<small class="badge badge-warning">Em Análise</small>';
        }elseif($this->status == 4){
            return '<small class="badge badge-primary">Aguardando Aceite</small>';
        }elseif($this->status == 3){
            return '<small class="badge badge-danger">Cancelado</small>';
        }elseif($this->status == 2){
            return '<small class="badge badge-success">Aprovado</small>';
        }elseif($this->status == 1){
            return '<small class="badge badge-secondary">Entregando</small>'; 
        }elseif($this->status == 0){
            return '<small class="badge badge-info">Finalizado</small>'; 
        }else{
            return '<small class="badge badge-warning">Em Análise</small>'; 
        }
    }

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
