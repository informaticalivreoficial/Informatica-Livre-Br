<?php

namespace App\Repositories;

use App\Models\Portifolio;
use App\Models\PortifolioGb;
use Illuminate\Support\Facades\Storage;

class PortifolioRepository
{
    protected $model, $modelGb;
    protected $totalPage = 25;

    public function __construct(Portifolio $model, PortifolioGb $modelGb)
    {
        $this->model = $model;
        $this->modelGb = $modelGb;
    }

    public function getAll()
    {
        $results = $this->model->orderBy('created_at', 'DESC')
                                ->orderBy('status', 'ASC')
                                ->paginate($this->totalPage);
        return $results;
    }

    public function getById(int $id)
    {
        $result = $this->model->where('id', $id)->first();                                
        return $result;
    }

    public function create(array $data) 
    {
        $this->model->fill($data);
        $this->model->setSlug();
        $this->model->save();
        return $this->model;
    }

    public function update(array $data, int $id) 
    {        
        $update = $this->model->where('id', $id)->first();
        $data['cat_pai'] = $update->categoriaObject->id_pai;
        $update->fill($data);
        $update->save();
        $update->setSlug();
        return $update;
    }

    public function delete(int $id) 
    {
        $delete = $this->model->where('id', $id)->first();
        $delete->delete();
    }

    public function search(string $filter)
    {  
        $results = $this->model->where('name', 'LIKE', "%{$filter}%")
                                ->orWhere('content', 'LIKE', "%{$filter}%")
                                ->paginate($this->totalPage);
        return $results;
    }

    public function getGb(int $fk)
    {
        $gb = $this->modelGb->where('portifolio', $fk)->first();
        return $gb;
    }

    public function gbSetCover(int $gbId)
    {
        $imageSetCover = $this->modelGb->where('id', $gbId)->first();
        $allImage = $this->modelGb->where('portifolio', $imageSetCover->portifolio)->get();

        foreach ($allImage as $image) {
            $image->cover = null;
            $image->save();
        }

        $imageSetCover->cover = true;
        $imageSetCover->save();
        return true;
    }

    public function removeGb(int $id)
    {      
        $imageDelete = $this->modelGb->where('id', $id)->first();
        Storage::delete($imageDelete->path);
        $imageDelete->delete();
        return true;          
    }

    public function removeGbAll(int $id)
    {      
        $imageDelete = $this->modelGb->where('portifolio', $id)->first();
        Storage::delete($imageDelete->path);
        $imageDelete->delete();
        Storage::deleteDirectory('portifolio/'.$id);
    }

    public function setStatus($id, $status)
    {
        $set = $this->model->find($id);
        $set->status = $status;
        $set->save();
        return true;
    }
}