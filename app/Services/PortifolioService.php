<?php

namespace App\Services;

use App\Repositories\PortifolioRepository;

class PortifolioService
{
    protected $portifolioRepository;

    public function __construct(PortifolioRepository $portifolioRepository)
    {
        $this->portifolioRepository = $portifolioRepository;
    }

    public function getPortifolios()
    {
        return $this->portifolioRepository->getAll();
    }

    public function getPortifolio(int $id)
    {
        return $this->portifolioRepository->getById($id);
    }

    public function createPortifolio(array $data)
    {       
        return $this->portifolioRepository->create($data);
    }

    public function updatePortifolio(array $data, int $id)
    {       
        return $this->portifolioRepository->update($data, $id);
    }

    public function deletePortifolio(int $id)
    {
        return $this->portifolioRepository->delete($id);
    }

    public function searchPortifolio(string $filter)
    {        
        return $this->portifolioRepository->search($filter);
    }

    public function setCover(int $gbId)
    {      
        $setCover =  $this->portifolioRepository->gbSetCover($gbId);
        if($setCover){
            return ['success' => true];
        }          
    }

    public function portifolioSetStatus(int $id, $status)
    {      
        $portifolio =  $this->portifolioRepository->setStatus($id, $status);
        if($portifolio){
            return ['success' => true];
        }          
    }

    public function imageRemoveGb(int $id)
    {      
        $image =  $this->portifolioRepository->removeGb($id);
        if($image){
            return ['success' => true];
        }          
    }

    public function imageRemoveGbAll(int $id)
    {      
        return  $this->portifolioRepository->removeGbAll($id);
    }

    public function getGbImage(int $fk)
    {
        return $this->portifolioRepository->getGb($fk);
    }
}