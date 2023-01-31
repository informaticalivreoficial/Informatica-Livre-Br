<?php

namespace App\Services;

use App\Repositories\EstadoRepository;

class EstadoService
{
    protected $estadoRepository;

    public function __construct(EstadoRepository $estadoRepository)
    {
        $this->estadoRepository = $estadoRepository;
    }

    public function getEstados()
    {
        $estados = $this->estadoRepository->getEstados();
        return $estados;
    }

    public function getEstado($id)
    {
        $estados = $this->estadoRepository->getEstadoById($id);
        return $estados;
    }
}