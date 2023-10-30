<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CatServicoRequest;
use App\Http\Requests\Admin\ServicoRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\CatServico;
use App\Models\GbServico;
use App\Models\Servico;
use Illuminate\Http\Request;

class ServicoController extends Controller
{
    public function index()
    {
        $servicos = Servico::orderBy('created_at', 'DESC')->paginate(25);

        return view('admin.servicos.index', [
            'servicos' => $servicos
        ]);
    }

    public function create()
    {
        $catServicos = CatServico::orderBy('titulo', 'ASC')->available()->whereNull('id_pai')->get();

        return view('admin.servicos.create',[
            'catServicos' => $catServicos
        ]);
    }

    public function store(ServicoRequest $request)
    {
        $servicoCreate = Servico::create($request->all()); 
        $validator = Validator::make($request->only('files'), ['files.*' => 'image']);

        if ($validator->fails() === true) {
            return Redirect::back()->withInput()->with([
                'color' => 'orange',
                'message' => 'Todas as imagens devem ser do tipo jpg, jpeg ou png.',
            ]);
        }

        if ($request->allFiles()) {
            foreach ($request->allFiles()['files'] as $image) {
                $servicoImage = new GbServico();
                $servicoImage->servico = $servicoCreate->id;
                $servicoImage->path = $image->storeAs('servicos/' . $servicoCreate->id, Str::slug($request->name) . '-' . str_replace('.', '', microtime(true)) . '.' . $image->extension());
                $servicoImage->save();
                unset($servicoImage);
            }
        }
        
        return Redirect::route('servicos.edit', $servicoCreate->id)->with([
            'color' => 'success', 
            'message' => 'Serviço cadastrado com sucesso!'
        ]);        
    }

    public function edit($id)
    {
        $catServicos = CatServico::orderBy('created_at', 'DESC')
                                ->whereNull('id_pai')
                                ->available()
                                ->get();
        $servico = Servico::where('id', $id)->first();    
        return view('admin.servicos.edit', [
            'servico' => $servico,
            'catServicos' => $catServicos
        ]);
    }

    public function update(ServicoRequest $request, $id)
    {     
        $servicoUpdate = Servico::where('id', $id)->first();
        $servicoUpdate->fill($request->all());

        $servicoUpdate->save();
        $servicoUpdate->setSlug();

        $validator = Validator::make($request->only('files'), ['files.*' => 'image']);

        if ($validator->fails() === true) {
            return Redirect::back()->withInput()->with([
                'color' => 'orange',
                'message' => 'Todas as imagens devem ser do tipo jpg, jpeg ou png.',
            ]);
        }

        if ($request->allFiles()) {
            foreach ($request->allFiles()['files'] as $image) {
                $servicoImage = new GbServico();
                $servicoImage->servico = $servicoUpdate->id;
                $servicoImage->path = $image->storeAs('servicos/' . $servicoUpdate->id, Str::slug($request->name) . '-' . str_replace('.', '', microtime(true)) . '.' . $image->extension());
                $servicoImage->save();
                unset($servicoImage);
            }
        }

        return Redirect::route('servicos.edit', [
            'id' => $servicoUpdate->id
        ])->with(['color' => 'success', 'message' => 'Serviço atualizado com sucesso!']);
    } 

    public function search(Request $request)
    {
        $filters = $request->only('filter');

        $servicos = Servico::where('name', 'LIKE', "%{$filters}%")
                            ->orWhere('content', 'LIKE', "%{$filters}%")
                            ->paginate(25);

        return view('admin.servicos.index',[
            'servicos' => $servicos,
            'filters' => $filters
        ]);
    }

    public function imageSetCover(Request $request)
    {
        $imageSetCover = GbServico::where('id', $request->image)->first();
        $allImage = GbServico::where('servico', $imageSetCover->servico)->get();
        foreach ($allImage as $image) {
            $image->cover = null;
            $image->save();
        }
        $imageSetCover->cover = true;
        $imageSetCover->save();
        $json = [
            'success' => true,
        ];
        return response()->json($json);
    }

    public function imageRemove(Request $request)
    {
        $imageDelete = GbServico::where('id', $request->image)->first();
        Storage::delete($imageDelete->path);
        $imageDelete->delete();
        $json = [
            'success' => true,
        ];
        return response()->json($json);
    }
    
    public function servicoSetStatus(Request $request)
    {   
        $servico = Servico::find($request->id);
        $servico->status = $request->status;
        $servico->save();
        return response()->json(['success' => true]);
    }

    public function delete(Request $request)
    {
        $servicodelete = Servico::where('id', $request->id)->first();
        $servicoGb = GbServico::where('servico', $servicodelete->id)->first();
        $nome = \App\Helpers\Renato::getPrimeiroNome(Auth::user()->name);

        if(!empty($servicodelete)){
            if(!empty($servicoGb)){
                $json = [
                    'error' => "<b>$nome</b> você tem certeza que deseja excluir este serviço? Existem imagens adicionadas e todas serão excluídas!",
                    'id' => $servicodelete->id
                ];                
            }else{
                $json = [
                    'error' => "<b>$nome</b> você tem certeza que deseja excluir este serviço?",
                    'id' => $servicodelete->id
                ]; 
            }            
        }else{
            $json = ['error' => 'Erro ao excluir'];
        }
        return response()->json($json);
    }
    
    public function deleteon(Request $request)
    {
        $servicodelete = Servico::where('id', $request->servico_id)->first();  
        $imageDelete = GbServico::where('servico', $servicodelete->id)->first();
        
        if(!empty($servicodelete)){
            if(!empty($imageDelete)){
                $imageDelete = GBServico::where('servico', $servicodelete->id)->first();
                Storage::delete($imageDelete->path);
                $imageDelete->delete();
                Storage::deleteDirectory('servicos/'.$servicodelete->id);               
            }
            $servicodelete->delete();
        }
        return Redirect::route('servicos.index')->with([
            'color' => 'success', 
            'message' => 'O serviço '.$servicodelete->name.' foi removido com sucesso!'
        ]);
    }

    public function categorias()
    {
        $categorias = CatServico::where('id_pai', null)->orderBy('tipo', 'ASC')
                    ->orderBy('status', 'ASC')
                    ->orderBy('created_at', 'DESC')->paginate(25);
        return view('admin.servicos.categorias',[
            'categorias' => $categorias
        ]);
    }

    public function categoriaCreate(Request $request, $catpai)
    {        
        $catpai = CatServico::where('id', $request->catpai)->first();
        
        return view('admin.servicos.categoria-create',[
            'catpai' => $catpai
        ]);
    }

    public function categoriaStore(CatServicoRequest $request)
    {
        $criarCategoria = CatServico::create($request->all());
        $criarCategoria->fill($request->all());

        $criarCategoria->setSlug();
        
        if($request->id_pai != null){
            return Redirect::route('servicos-categorias.edit', [
                'id' => $criarCategoria->id,
            ])->with(['color' => 'success', 'message' => 'Sub Categoria cadastrada com sucesso!']);
        }else{
            return Redirect::route('servicos-categorias.edit', [
                'id' => $criarCategoria->id,
            ])->with(['color' => 'success', 'message' => 'Categoria cadastrada com sucesso!']);
        }
    }

    public function categoriaEdit($id)
    {
        $categoria = CatServico::where('id', $id)->first();
        if($categoria->id_pai != 'null'){
            $catpai = CatServico::where('id', $categoria->id_pai)->first();
        }else{
            $catpai = 'null';
        }
        return view('admin.servicos.categoria-edit', [
            'categoria' => $categoria,
            'catpai' => $catpai
        ]);
    }

    public function categoriaUpdate(CatServicoRequest $request, $id)
    {
        $categoria = CatServico::where('id', $id)->first();
        $categoria->fill($request->all());

        $categoria->save();
        $categoria->setSlug();
        
        if($categoria->id_pai != null){
            return Redirect::route('servicos-categorias.edit', [
                'id' => $categoria->id,
            ])->with(['color' => 'success', 'message' => 'Sub Categoria atualizada com sucesso!']);
        }else{
            return Redirect::route('servicos-categorias.edit', [
                'id' => $categoria->id,
            ])->with(['color' => 'success', 'message' => 'Categoria atualizada com sucesso!']);
        }
        
    }

    public function categoriaDelete(Request $request)
    {
        $categoria = CatServico::where('id', $request->id)->first();
        $subcategoria = CatServico::where('id_pai', $request->id)->first();
        $produtos = Servico::where('categoria', $request->id)->first();
        $nome = \App\Helpers\Renato::getPrimeiroNome(Auth::user()->name);

        if(!empty($categoria) && empty($subcategoria)){
            if($categoria->id_pai == null){
                $json = "<b>$nome</b> você tem certeza que deseja excluir esta categoria?";
                return response()->json(['erroron' => $json,'id' => $categoria->id]);
            }else{
                // se tiver posts
                if(!empty($produtos)){
                    $json = "<b>$nome</b> você tem certeza que deseja excluir esta sub categoria? Ela possui serviços e tudo será excluído!";
                    return response()->json(['erroron' => $json,'id' => $categoria->id]);
                }else{
                    $json = "<b>$nome</b> você tem certeza que deseja excluir esta sub categoria?";
                    return response()->json(['erroron' => $json,'id' => $categoria->id]);
                }                
            }            
        }
        if(!empty($categoria) && !empty($subcategoria)){
            $json = "<b>$nome</b> esta categoria possui sub categorias! É peciso excluílas primeiro!";
            return response()->json(['error' => $json,'id' => $categoria->id]);
        }else{
            return response()->json(['error' => 'Erro ao excluir']);
        }        
    }

    public function categoriaDeleteon(Request $request)
    {
        $categoria = CatServico::where('id', $request->categoria_id)->first();  
        $produto = Servico::where('categoria', $request->id)->first();
        
        $categoriaR = $categoria->titulo;

        if(!empty($categoria)){
            if(!empty($produto)){
                $produtogb = GbServico::where('servico', $produto->id)->first();
                if(!empty($produtogb)){
                    Storage::delete($produtogb->path);
                    $produtogb->delete();
                }
                
                Storage::deleteDirectory('servicos/'.$produto->id);
                $categoria->delete();
            }
            $categoria->delete();
        }

        if($categoria->id_pai != null){
            return Redirect::route('catservicos.index')->with([
                'color' => 'success', 
                'message' => 'A sub categoria '.$categoriaR.' foi removida com sucesso!'
            ]);
        }else{
            return Redirect::route('catservicos.index')->with([
                'color' => 'success', 
                'message' => 'A categoria '.$categoriaR.' foi removida com sucesso!'
            ]);
        }        
    }
}
