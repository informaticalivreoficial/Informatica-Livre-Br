<?php

namespace App\Repositories;

use App\Models\Cidades;

class CidadeRepository
{
    protected $model;

    public function __construct(Cidades $model)
    {
        $this->model = $model;
    }

    public function getCidades()
    {
        $cidades = $this->model->orderBy('cidade_nome', 'ASC')->get();
        return $cidades;
    }

    public function getCidadesById(int $id)
    {
        $cidade = $this->model->where('cidade_id', $id)->first();
        return $cidade;
    }

    public function getCidadeById(int $id)
    {
        $cidade = $this->model->where('cidade_id', $id)->first();
        return $cidade;
    }

    public function getCidadesByUfId(int $id)
    {
        $cidades = $this->model->where('estado_id', $id)->get(["cidade_nome", "cidade_id"]);
        return $cidades;
    }
}