<?php

namespace App\Services;

use App\Repositories\CatProdutoRepository;

class CatProdutoService
{
    private $catProdutoRepository;

    public function __construct(CatProdutoRepository $catProdutoRepository)
    {
        $this->catProdutoRepository = $catProdutoRepository;
    }

    public function getCategorias()
    {
        return $this->catProdutoRepository->getCategoriasAll();
    }

    public function getCategoria(int $id)
    {
        //
    }
}