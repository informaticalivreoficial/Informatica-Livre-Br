<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CatProduto as CatProdutoRequest;
use App\Models\CatProduto;
use App\Models\Produto;
use App\Models\ProdutoGb;
use CoffeeCode\Cropper\Cropper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CatProdutoController extends Controller
{
    public function index()
    {
        $categorias = CatProduto::where('id_pai', null)->orderBy('tipo', 'ASC')
                    ->orderBy('status', 'ASC')
                    ->orderBy('created_at', 'DESC')->paginate(25);
        return view('admin.produtos.categorias',[
            'categorias' => $categorias
        ]);
    }

    public function create(Request $request, $catpai)
    {        
        $catpai = CatProduto::where('id', $request->catpai)->first();
        
        return view('admin.produtos.categoria-create',[
            'catpai' => $catpai
        ]);
    }

    public function store(CatProdutoRequest $request)
    {
        $criarCategoria = CatProduto::create($request->all());
        $criarCategoria->fill($request->all());

        $criarCategoria->setSlug();
        
        if($request->id_pai != null){
            return redirect()->route('produtos-categorias.edit', [
                'id' => $criarCategoria->id,
            ])->with(['color' => 'success', 'message' => 'Sub Categoria cadastrada com sucesso!']);
        }else{
            return redirect()->route('produtos-categorias.edit', [
                'id' => $criarCategoria->id,
            ])->with(['color' => 'success', 'message' => 'Categoria cadastrada com sucesso!']);
        }
    }

    public function edit($id)
    {
        $categoria = CatProduto::where('id', $id)->first();
        if($categoria->id_pai != 'null'){
            $catpai = CatProduto::where('id', $categoria->id_pai)->first();
        }else{
            $catpai = 'null';
        }
        return view('admin.produtos.categoria-edit', [
            'categoria' => $categoria,
            'catpai' => $catpai
        ]);
    }

    public function update(CatProdutoRequest $request, $id)
    {
        $categoria = CatProduto::where('id', $id)->first();
        $categoria->fill($request->all());

        $categoria->save();
        $categoria->setSlug();
        
        if($categoria->id_pai != null){
            return redirect()->route('produtos-categorias.edit', [
                'id' => $categoria->id,
            ])->with(['color' => 'success', 'message' => 'Sub Categoria atualizada com sucesso!']);
        }else{
            return redirect()->route('produtos-categorias.edit', [
                'id' => $categoria->id,
            ])->with(['color' => 'success', 'message' => 'Categoria atualizada com sucesso!']);
        }
        
    }

    public function delete(Request $request)
    {
        $categoria = CatProduto::where('id', $request->id)->first();
        $subcategoria = CatProduto::where('id_pai', $request->id)->first();
        $produtos = Produto::where('categoria', $request->id)->first();
        $nome = getPrimeiroNome(Auth::user()->name);

        if(!empty($categoria) && empty($subcategoria)){
            if($categoria->id_pai == null){
                $json = "<b>$nome</b> você tem certeza que deseja excluir esta categoria?";
                return response()->json(['erroron' => $json,'id' => $categoria->id]);
            }else{
                // se tiver posts
                if(!empty($produtos)){
                    $json = "<b>$nome</b> você tem certeza que deseja excluir esta sub categoria? Ela possui produtos e tudo será excluído!";
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
        $categoria = CatProduto::where('id', $request->categoria_id)->first();  
        $produto = Produto::where('categoria', $request->id)->first();
        
        $categoriaR = $categoria->titulo;

        if(!empty($categoria)){
            if(!empty($produto)){
                $produtogb = ProdutoGb::where('produto', $produto->id)->first();
                if(!empty($produtogb)){
                    Storage::delete($produtogb->path);
                    Cropper::flush($produtogb->path);
                    $produtogb->delete();
                }
                
                Storage::deleteDirectory('produtos/'.$produto->id);
                $categoria->delete();
            }
            $categoria->delete();
        }

        if($categoria->id_pai != null){
            return redirect()->route('catprodutos.index')->with([
                'color' => 'success', 
                'message' => 'A sub categoria '.$categoriaR.' foi removida com sucesso!'
            ]);
        }else{
            return redirect()->route('catprodutos.index')->with([
                'color' => 'success', 
                'message' => 'A categoria '.$categoriaR.' foi removida com sucesso!'
            ]);
        }        
    }
}
