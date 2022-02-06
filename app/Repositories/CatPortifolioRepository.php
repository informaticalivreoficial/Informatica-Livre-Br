<?php

namespace App\Repositories;

use App\Models\CatPortifolio;

class CatPortifolioRepository
{
    private $model;

    public function __construct(CatPortifolio $model)
    {
        $this->model = $model;
    }

    public function getCategoriasAll()
    {
        $catPortifolios = $this->model->orderBy('created_at', 'DESC')
                                   ->whereNull('id_pai')
                                   ->available()
                                   ->get();
        return $catPortifolios;       
    }

    public function getCategoriaById()
    {
        //
    }
}