<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CatServicoRequest;
use App\Models\CatServico;
use App\Models\GbServico;
use App\Models\Servico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class CatServicoController extends Controller
{
    public function index()
    {
        $categorias = CatServico::where('id_pai', null)->orderBy('tipo', 'ASC')
                    ->orderBy('status', 'ASC')
                    ->orderBy('created_at', 'DESC')->paginate(25);
        return view('admin.servicos.categorias',[
            'categorias' => $categorias
        ]);
    }

    public function create(Request $request, $catpai)
    {        
        $catpai = CatServico::where('id', $request->catpai)->first();
        
        return view('admin.servicos.categoria-create',[
            'catpai' => $catpai
        ]);
    }

    public function store(CatServicoRequest $request)
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

    public function edit($id)
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

    public function update(CatServicoRequest $request, $id)
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

    public function delete(Request $request)
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

    public function deleteon(Request $request)
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
