<?php

namespace App\Repositories;

use App\Models\Estados;

class EstadoRepository
{
    protected $model;

    public function __construct(Estados $model)
    {
        $this->model = $model;
    }

    public function getEstados()
    {
        $estados = $this->model->orderBy('estado_nome', 'ASC')->get();
        return $estados;
    }

    public function getEstadoById(int $id)
    {
        $estado = $this->model->where('estado_id', $id)->first();
        return $estado;
    }
}