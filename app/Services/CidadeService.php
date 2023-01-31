<?php

namespace App\Services;

use App\Repositories\CidadeRepository;

class CidadeService
{
    protected $cidadeRepository;

    public function __construct(CidadeRepository $cidadeRepository)
    {
        $this->cidadeRepository = $cidadeRepository;
    }

    public function getCidades()
    {
        $cidades = $this->cidadeRepository->getCidades();
        return $cidades;
    }

    public function getCidadeById(int $id)
    {
        $cidade = $this->cidadeRepository->getCidadeById($id);
        return $cidade;
    }

    public function getCidadesByUfId(int $id)
    {
        $cidades = $this->cidadeRepository->getCidadesByUfId($id);
        return $cidades;
    }
}