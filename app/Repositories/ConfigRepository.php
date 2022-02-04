<?php

namespace App\Repositories;

use App\Models\Configuracoes;

class ConfigRepository
{
    protected $model;

    public function __construct(Configuracoes $model)
    {
        $this->model = $model;
    }

    public function getConfigById(int $id)
    {
        $config = $this->model->where('id', $id)->first();
        return $config;
    }
}