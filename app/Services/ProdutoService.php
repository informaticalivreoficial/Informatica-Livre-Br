<?php

namespace App\Services;

use App\Repositories\ProdutoRepository;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ProdutoService
{
    protected $produtoRepository;

    public function __construct(ProdutoRepository $produtoRepository)
    {
        $this->produtoRepository = $produtoRepository;
    }

    public function getProdutos()
    {
        return $this->produtoRepository->getAll();
    }

    public function getProduto(int $id)
    {
        return $this->produtoRepository->getById($id);
    }

    public function createProduto(array $data)
    {       
        return $this->produtoRepository->create($data);
    }

    public function updateProduto(array $data, int $id)
    {       
        return $this->produtoRepository->update($data, $id);
    }

    public function deleteProduto(int $id)
    {
        return $this->produtoRepository->delete($id);
    }

    public function searchProduto(string $filter)
    {        
        return $this->produtoRepository->search($filter);
    }

    public function setCover(int $gbId)
    {      
        $setCover =  $this->produtoRepository->gbSetCover($gbId);
        if($setCover){
            return ['success' => true];
        }          
    }

    public function produtoSetStatus(int $id, $status)
    {      
        $produto =  $this->produtoRepository->setStatus($id, $status);
        if($produto){
            return ['success' => true];
        }          
    }

    public function imageRemoveGb(int $id)
    {      
        $image =  $this->produtoRepository->removeGb($id);
        if($image){
            return ['success' => true];
        }          
    }

    public function imageRemoveGbAll(int $id)
    {      
        return  $this->produtoRepository->removeGbAll($id);
    }

    public function getGbImage(int $fk)
    {
        return $this->produtoRepository->getGb($fk);
    }
}