<?php

namespace App\Services;

use App\Repositories\CatPortifolioRepository;

class CatPortifolioService
{
    private $catPortifolioRepository;

    public function __construct(CatPortifolioRepository $catPortifolioRepository)
    {
        $this->catPortifolioRepository = $catPortifolioRepository;
    }

    public function getCategorias()
    {
        return $this->catPortifolioRepository->getCategoriasAll();
    }

    public function getCategoria(int $id)
    {
        //
    }
}