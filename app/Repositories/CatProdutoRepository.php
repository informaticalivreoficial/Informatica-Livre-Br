<?php

namespace App\Repositories;

use App\Models\CatProduto;

class CatProdutoRepository
{
    private $model;

    public function __construct(CatProduto $model)
    {
        $this->model = $model;
    }

    public function getCategoriasAll()
    {
        $catProdutos = $this->model->orderBy('created_at', 'DESC')
                                   ->whereNull('id_pai')
                                   ->available()
                                   ->get();
        return $catProdutos;       
    }

    public function getCategoriaById()
    {
        //
    }
}