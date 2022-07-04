<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CatPortifolioRequest;
use App\Models\CatPortifolio;
use App\Models\Portifolio;
use App\Models\PortifolioGb;
use App\Support\Cropper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class CatPortifolioController extends Controller
{
    public function index()
    {
        $categorias = CatPortifolio::where('id_pai', null)->orderBy('tipo', 'ASC')
                    ->orderBy('status', 'ASC')
                    ->orderBy('created_at', 'DESC')->paginate(25);
        return view('admin.portifolio.categorias',[
            'categorias' => $categorias
        ]);
    }

    public function create(Request $request, $catpai)
    {        
        $catpai = CatPortifolio::where('id', $request->catpai)->first();
        
        return view('admin.portifolio.categoria-create',[
            'catpai' => $catpai
        ]);
    }

    public function store(CatPortifolioRequest $request)
    {
        $criarCategoria = CatPortifolio::create($request->all());
        $criarCategoria->fill($request->all());

        $criarCategoria->setSlug();
        
        if($request->id_pai != null){
            return Redirect::route('portifolio-categorias.edit', [
                'id' => $criarCategoria->id,
            ])->with(['color' => 'success', 'message' => 'Sub Categoria cadastrada com sucesso!']);
        }else{
            return Redirect::route('portifolio-categorias.edit', [
                'id' => $criarCategoria->id,
            ])->with(['color' => 'success', 'message' => 'Categoria cadastrada com sucesso!']);
        }
    }

    public function edit($id)
    {
        $categoria = CatPortifolio::where('id', $id)->first();
        if($categoria->id_pai != 'null'){
            $catpai = CatPortifolio::where('id', $categoria->id_pai)->first();
        }else{
            $catpai = 'null';
        }
        return view('admin.portifolio.categoria-edit', [
            'categoria' => $categoria,
            'catpai' => $catpai
        ]);
    }

    public function update(CatPortifolioRequest $request, $id)
    {
        $categoria = CatPortifolio::where('id', $id)->first();
        $categoria->fill($request->all());

        $categoria->save();
        $categoria->setSlug();
        
        if($categoria->id_pai != null){
            return Redirect::route('portifolio-categorias.edit', [
                'id' => $categoria->id,
            ])->with(['color' => 'success', 'message' => 'Sub Categoria atualizada com sucesso!']);
        }else{
            return Redirect::route('portifolio-categorias.edit', [
                'id' => $categoria->id,
            ])->with(['color' => 'success', 'message' => 'Categoria atualizada com sucesso!']);
        }
        
    }

    public function delete(Request $request)
    {
        $categoria = CatPortifolio::where('id', $request->id)->first();
        $subcategoria = CatPortifolio::where('id_pai', $request->id)->first();
        $portifolio = Portifolio::where('categoria', $request->id)->first();
        $nome = \App\Helpers\Renato::getPrimeiroNome(Auth::user()->name);

        if(!empty($categoria) && empty($subcategoria)){
            if($categoria->id_pai == null){
                $json = "<b>$nome</b> você tem certeza que deseja excluir esta categoria?";
                return response()->json(['erroron' => $json,'id' => $categoria->id]);
            }else{
                // se tiver posts
                if(!empty($portifolio)){
                    $json = "<b>$nome</b> você tem certeza que deseja excluir esta sub categoria? Ela possui projetos e tudo será excluído!";
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

    public function deleteon(Request $request)
    {
        $categoria = CatPortifolio::where('id', $request->categoria_id)->first();  
        $portifolio = Portifolio::where('categoria', $request->id)->first();
        
        $categoriaR = $categoria->titulo;

        if(!empty($categoria)){
            if(!empty($portifolio)){
                $portifoliogb = PortifolioGb::where('portifolio', $portifolio->id)->first();
                if(!empty($produtogb)){
                    Storage::delete($portifoliogb->path);
                    Cropper::flush($portifoliogb->path);
                    $produtogb->delete();
                }
                
                Storage::deleteDirectory('portifolio/'.$portifolio->id);
                $categoria->delete();
            }
            $categoria->delete();
        }

        if($categoria->id_pai != null){
            return Redirect::route('catportifolio.index')->with([
                'color' => 'success', 
                'message' => 'A sub categoria '.$categoriaR.' foi removida com sucesso!'
            ]);
        }else{
            return Redirect::route('catportifolio.index')->with([
                'color' => 'success', 
                'message' => 'A categoria '.$categoriaR.' foi removida com sucesso!'
            ]);
        }        
    }
}
