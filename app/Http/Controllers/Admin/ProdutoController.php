<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\Produto as ProdutoRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Image;
use App\Support\Cropper;
use App\Models\CatProduto;
use App\Models\Produto;
use App\Models\ProdutoGb;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index()
    {
        $produtos = Produto::orderBy('created_at', 'DESC')->orderBy('status', 'ASC')->paginate(25);
        return view('admin.produtos.index', [
            'produtos' => $produtos,
        ]);
    }

    public function create()
    {
        $catProdutos = CatProduto::orderBy('created_at', 'DESC')->whereNull('id_pai')->available()->get();
        return view('admin.produtos.create',[
            'catProdutos' => $catProdutos
        ]);
    }

    public function store(ProdutoRequest $request)
    {
        $produtoCreate = Produto::create($request->all());
        $produtoCreate->fill($request->all());

        $validator = Validator::make($request->only('files'), ['files.*' => 'image']);

        if ($validator->fails() === true) {
            return redirect()->back()->withInput()->with([
                'color' => 'orange',
                'message' => 'Todas as imagens devem ser do tipo jpg, jpeg ou png.',
            ]);
        }

        if ($request->allFiles()) {
            foreach ($request->allFiles()['files'] as $image) {
                $produtoGb = new ProdutoGb();
                $produtoGb->produto = $produtoCreate->id;
                $produtoGb->path = $image->storeAs('produtos/' . $produtoCreate->id, Str::slug($request->name) . '-' . str_replace('.', '', microtime(true)) . '.' . $image->extension());
                $produtoGb->save();
                unset($produtoGb);
            }
        }
        
        return redirect()->route('produtos.edit', $produtoCreate->id)->with([
            'color' => 'success', 
            'message' => 'Produto cadastrado com sucesso!'
        ]);        
    }

    public function edit($id)
    {
        $catProdutos = CatProduto::orderBy('created_at', 'DESC')->whereNull('id_pai')->available()->get();
        $produto = Produto::where('id', $id)->first();    
        return view('admin.produtos.edit', [
            'produto' => $produto,
            'catProdutos' => $catProdutos
        ]);
    }

    public function update(ProdutoRequest $request, $id)
    {     
        $produto = Produto::where('id', $id)->first();
        $produto->fill($request->all());

        $produto->save();
        $produto->setSlug();

        $validator = Validator::make($request->only('files'), ['files.*' => 'image']);

        if ($validator->fails() === true) {
            return redirect()->back()->withInput()->with([
                'color' => 'orange',
                'message' => 'Todas as imagens devem ser do tipo jpg, jpeg ou png.',
            ]);
        }

        if ($request->allFiles()) {
            foreach ($request->allFiles()['files'] as $image) {
                $produtoImage = new ProdutoGb();
                $produtoImage->produto_id = $produto->id;
                $produtoImage->path = $image->storeAs('produtos/' . $produto->id, Str::slug($request->name) . '-' . str_replace('.', '', microtime(true)) . '.' . $image->extension());
                $produtoImage->save();
                unset($produtoImage);
            }
        }

        return redirect()->route('produtos.edit', [
            'id' => $produto->id,
        ])->with(['color' => 'success', 'message' => 'Produto atualizado com sucesso!']);
    } 

    public function imageSetCover(Request $request)
    {
        $imageSetCover = ProdutoGb::where('id', $request->image)->first();
        $allImage = ProdutoGb::where('produto', $imageSetCover->produto)->get();

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
        $imageDelete = ProdutoGb::where('id', $request->image)->first();
        Storage::delete($imageDelete->path);
        Cropper::flush($imageDelete->path);
        $imageDelete->delete();

        $json = [
            'success' => true,
        ];
        return response()->json($json);
    }
    
    public function produtoSetStatus(Request $request)
    {        
        $produto = Produto::find($request->id);
        $produto->status = $request->status;
        $produto->save();
        return response()->json(['success' => true]);
    }

    public function delete(Request $request)
    {
        $produtodelete = Produto::where('id', $request->id)->first();
        $produtoGb = ProdutoGb::where('produto', $produtodelete->id)->first();
        $nome = getPrimeiroNome(Auth::user()->name);

        if(!empty($produtodelete)){
            if(!empty($produtoGb)){
                $json = "<b>$nome</b> você tem certeza que deseja excluir este produto? Existem imagens adicionadas e todas serão excluídas!";
                return response()->json(['error' => $json,'id' => $produtodelete->id]);
            }else{
                $json = "<b>$nome</b> você tem certeza que deseja excluir este produto?";
                return response()->json(['error' => $json,'id' => $produtodelete->id]);
            }            
        }else{
            return response()->json(['error' => 'Erro ao excluir']);
        }
    }
    
    public function deleteon(Request $request)
    {
        $produtodelete = Produto::where('id', $request->produto_id)->first();  
        $imageDelete = ProdutoGb::where('produto', $produtodelete->id)->first();
        $postR = $produtodelete->name;

        if(!empty($produtodelete)){
            if(!empty($imageDelete)){
                Storage::delete($imageDelete->path);
                Cropper::flush($imageDelete->path);
                $imageDelete->delete();
                Storage::deleteDirectory('produtos/'.$produtodelete->id);
                $produtodelete->delete();
            }
            $produtodelete->delete();
        }
        return redirect()->route('produtos.index')->with([
            'color' => 'success', 
            'message' => 'O produto '.$postR.' foi removido com sucesso!'
        ]);
    }
}
