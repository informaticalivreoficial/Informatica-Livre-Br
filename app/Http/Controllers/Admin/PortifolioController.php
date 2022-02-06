<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PortifolioRequest;
use App\Models\Empresa;
use App\Models\PortifolioGb;
use App\Services\CatPortifolioService;
use App\Services\PortifolioService;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class PortifolioController extends Controller
{
    private $portifolioService, $catPortifolioService;

    public function __construct(PortifolioService $portifolioService, CatPortifolioService $catPortifolioService)
    {
        $this->portifolioService = $portifolioService;
        $this->catPortifolioService = $catPortifolioService;
    }

    public function index()
    {
        $portifolios = $this->portifolioService->getPortifolios();
        return view('admin.portifolio.index',[
            'portifolios' => $portifolios
        ]);
    }

    public function create()
    {
        $catPortifolio = $this->catPortifolioService->getCategorias();
        //refatorar depois para serviço
        $empresas = Empresa::orderBy('created_at', 'DESC')->get();

        return view('admin.portifolio.create',[
            'catPortifolio' => $catPortifolio,
            'empresas' => $empresas
        ]);
    }

    public function store(PortifolioRequest $request)
    {
        $portifolioCreate = $this->portifolioService->createPortifolio($request->all());        
        $validator = Validator::make($request->only('files'), ['files.*' => 'image']);

        if ($validator->fails() === true) {
            return Redirect::back()->withInput()->with([
                'color' => 'orange',
                'message' => 'Todas as imagens devem ser do tipo jpg, jpeg ou png.',
            ]);
        }

        if ($request->allFiles()) {
            foreach ($request->allFiles()['files'] as $image) {
                $portifolioImage = new PortifolioGb();
                $portifolioImage->portifolio = $portifolioCreate->id;
                $portifolioImage->path = $image->storeAs('portifolio/' . $portifolioCreate->id, Str::slug($request->name) . '-' . str_replace('.', '', microtime(true)) . '.' . $image->extension());
                $portifolioImage->save();
                unset($portifolioImage);
            }
        }
        
        return Redirect::route('portifolio.edit', $portifolioCreate->id)->with([
            'color' => 'success', 
            'message' => 'Projeto cadastrado com sucesso!'
        ]);        
    }

    public function edit($id)
    {
        $catPortifolio = $this->catPortifolioService->getCategorias();
        $projeto = $this->portifolioService->getPortifolio($id); 
        //refatorar depois para serviço
        $empresas = Empresa::orderBy('created_at', 'DESC')->available()->get();   
        return view('admin.portifolio.edit', [
            'projeto' => $projeto,
            'catPortifolio' => $catPortifolio,
            'empresas' => $empresas
        ]);
    }

    public function update(PortifolioRequest $request, $id)
    {     
        $portifolioUpdate = $this->portifolioService->updatePortifolio($request->all(), $id);

        $validator = Validator::make($request->only('files'), ['files.*' => 'image']);

        if ($validator->fails() === true) {
            return Redirect::back()->withInput()->with([
                'color' => 'orange',
                'message' => 'Todas as imagens devem ser do tipo jpg, jpeg ou png.',
            ]);
        }

        if ($request->allFiles()) {
            foreach ($request->allFiles()['files'] as $image) {
                $portifolioImage = new PortifolioGb();
                $portifolioImage->portifolio = $portifolioUpdate->id;
                $portifolioImage->path = $image->storeAs('portifolio/' . $portifolioUpdate->id, Str::slug($request->name) . '-' . str_replace('.', '', microtime(true)) . '.' . $image->extension());
                $portifolioImage->save();
                unset($portifolioImage);
            }
        }

        return Redirect::route('portifolio.edit', [
            'id' => $portifolioUpdate->id
        ])->with(['color' => 'success', 'message' => 'Projeto atualizado com sucesso!']);
    }

    public function search(Request $request)
    {
        $filters = $request->only('filter');

        $projetos = $this->portifolioService->searchPortifolio($request->filter);

        return view('admin.portifolio.index',[
            'projetos' => $projetos,
            'filters' => $filters
        ]);
    }

    public function imageSetCover(Request $request)
    {
        $imageSetCover = $this->portifolioService->setCover($request->image);
        return response()->json($imageSetCover);
    }

    public function imageRemove(Request $request)
    {
        $imageDelete = $this->portifolioService->imageRemoveGb($request->image);
        return response()->json($imageDelete);
    }
    
    public function portifolioSetStatus(Request $request)
    {   
        $portifolioSetStatus = $this->portifolioService->portifolioSetStatus($request->id, $request->status);       
        return response()->json($portifolioSetStatus);
    }

    public function delete(Request $request)
    {
        $portifoliodelete = $this->portifolioService->getPortifolio($request->id);
        $portifolioGb = $this->portifolioService->getGbImage($portifoliodelete->id);
        $nome = getPrimeiroNome(Auth::user()->name);

        if(!empty($portifoliodelete)){
            if(!empty($portifolioGb)){
                $json = [
                    'error' => "<b>$nome</b> você tem certeza que deseja excluir este projeto? Existem imagens adicionadas e todas serão excluídas!",
                    'id' => $portifoliodelete->id
                ];                
            }else{
                $json = [
                    'error' => "<b>$nome</b> você tem certeza que deseja excluir este projeto?",
                    'id' => $portifoliodelete->id
                ]; 
            }            
        }else{
            $json = ['error' => 'Erro ao excluir'];
        }
        return response()->json($json);
    }
    
    public function deleteon(Request $request)
    {
        $portifoliodelete = $this->portifolioService->getPortifolio($request->portifolio_id); 
        $imageDelete = $this->portifolioService->getGbImage($portifoliodelete->id);
        
        if(!empty($portifoliodelete)){
            if(!empty($imageDelete)){
                $this->portifolioService->imageRemoveGbAll($portifoliodelete->id);                
            }
            $this->portifolioService->deletePortifolio($portifoliodelete->id);
        }
        return Redirect::route('portifolio.index')->with([
            'color' => 'success', 
            'message' => 'O projeto '.$portifoliodelete->name.' foi removido com sucesso!'
        ]);
    }
}