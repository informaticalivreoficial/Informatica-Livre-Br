<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Produto as ProdutoRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use App\Models\ProdutoGb;
use App\Services\CatProdutoService;
use App\Services\ProdutoService;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    private $produtoService, $catProdutoService;

    public function __construct(ProdutoService $produtoService, CatProdutoService $catProdutoService)
    {
        $this->produtoService = $produtoService;
        $this->catProdutoService = $catProdutoService;
    }

    public function index()
    {
        $produtos = $this->produtoService->getProdutos();

        return view('admin.produtos.index', [
            'produtos' => $produtos
        ]);
    }

    public function create()
    {
        $catProdutos = $this->catProdutoService->getCategorias();

        return view('admin.produtos.create',[
            'catProdutos' => $catProdutos
        ]);
    }

    public function store(ProdutoRequest $request)
    {
        $produtoCreate = $this->produtoService->createProduto($request->all());        

        $validator = Validator::make($request->only('files'), ['files.*' => 'image']);

        if ($validator->fails() === true) {
            return Redirect::back()->withInput()->with([
                'color' => 'orange',
                'message' => 'Todas as imagens devem ser do tipo jpg, jpeg ou png.',
            ]);
        }

        if ($request->allFiles()) {
            foreach ($request->allFiles()['files'] as $image) {
                $produtoImage = new ProdutoGb();
                $produtoImage->produto = $produtoCreate->id;
                $produtoImage->path = $image->storeAs('produtos/' . $produtoCreate->id, Str::slug($request->name) . '-' . str_replace('.', '', microtime(true)) . '.' . $image->extension());
                $produtoImage->save();
                unset($produtoImage);
            }
        }
        
        return Redirect::route('produtos.edit', $produtoCreate->id)->with([
            'color' => 'success', 
            'message' => 'Produto cadastrado com sucesso!'
        ]);        
    }

    public function edit($id)
    {
        $catProdutos = $this->catProdutoService->getCategorias();
        $produto = $this->produtoService->getProduto($id);    
        return view('admin.produtos.edit', [
            'produto' => $produto,
            'catProdutos' => $catProdutos
        ]);
    }

    public function update(ProdutoRequest $request, $id)
    {     
        $produtoUpdate = $this->produtoService->updateProduto($request->all(), $id);

        $validator = Validator::make($request->only('files'), ['files.*' => 'image']);

        if ($validator->fails() === true) {
            return Redirect::back()->withInput()->with([
                'color' => 'orange',
                'message' => 'Todas as imagens devem ser do tipo jpg, jpeg ou png.',
            ]);
        }

        if ($request->allFiles()) {
            foreach ($request->allFiles()['files'] as $image) {
                $produtoImage = new ProdutoGb();
                $produtoImage->produto = $produtoUpdate->id;
                $produtoImage->path = $image->storeAs('produtos/' . $produtoUpdate->id, Str::slug($request->name) . '-' . str_replace('.', '', microtime(true)) . '.' . $image->extension());
                $produtoImage->save();
                unset($produtoImage);
            }
        }

        return Redirect::route('produtos.edit', [
            'id' => $produtoUpdate->id
        ])->with(['color' => 'success', 'message' => 'Produto atualizado com sucesso!']);
    } 

    public function search(Request $request)
    {
        $filters = $request->only('filter');

        $produtos = $this->produtoService->searchProduto($request->filter);

        return view('admin.produtos.index',[
            'produtos' => $produtos,
            'filters' => $filters
        ]);
    }

    public function imageSetCover(Request $request)
    {
        $imageSetCover = $this->produtoService->setCover($request->image);
        return response()->json($imageSetCover);
    }

    public function imageRemove(Request $request)
    {
        $imageDelete = $this->produtoService->imageRemoveGb($request->image);
        return response()->json($imageDelete);
    }
    
    public function produtoSetStatus(Request $request)
    {   
        $produtoSetStatus = $this->produtoService->produtoSetStatus($request->id, $request->status);       
        return response()->json($produtoSetStatus);
    }

    public function delete(Request $request)
    {
        $produtodelete = $this->produtoService->getProduto($request->id);
        $produtoGb = $this->produtoService->getGbImage($produtodelete->id);
        $nome = getPrimeiroNome(Auth::user()->name);

        if(!empty($produtodelete)){
            if(!empty($produtoGb)){
                $json = [
                    'error' => "<b>$nome</b> você tem certeza que deseja excluir este produto? Existem imagens adicionadas e todas serão excluídas!",
                    'id' => $produtodelete->id
                ];                
            }else{
                $json = [
                    'error' => "<b>$nome</b> você tem certeza que deseja excluir este produto?",
                    'id' => $produtodelete->id
                ]; 
            }            
        }else{
            $json = ['error' => 'Erro ao excluir'];
        }
        return response()->json($json);
    }
    
    public function deleteon(Request $request)
    {
        $produtodelete = $this->produtoService->getProduto($request->produto_id); 
        $imageDelete = $this->produtoService->getGbImage($produtodelete->id);
        
        if(!empty($produtodelete)){
            if(!empty($imageDelete)){
                $this->produtoService->imageRemoveGbAll($produtodelete->id);                
            }
            $this->produtoService->deleteProduto($produtodelete->id);
        }
        return Redirect::route('produtos.index')->with([
            'color' => 'success', 
            'message' => 'O produto '.$produtodelete->name.' foi removido com sucesso!'
        ]);
    }
}