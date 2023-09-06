<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CofreRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Cofre;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class CofreController extends Controller
{
    public function index()
    {
        $items = Cofre::orderBy('created_at', 'DESC')->paginate(1);
        return view('admin.cofre.index', [
            'items' => $items
        ]);
    }

    public function create()
    {
        return view('admin.cofre.create');
    }
    
    public function store(CofreRequest $request)
    {
        $criarItem = Cofre::create($request->all());
        $criarItem->fill($request->all());

        if(!empty($request->file('logomarca'))){
            $criarItem->logomarca = $request->file('logomarca')->storeAs(env('AWS_PASTA') . 'cofre', Str::slug($request->name)  . '-' . str_replace('.', '', microtime(true)) . '.' . $request->file('logomarca')->extension());
            $criarItem->save();
        }
        
        return Redirect::route('cofre.edit', [
            'id' => $criarItem->id,
        ])->with(['color' => 'success', 'message' => 'Item cadastrado com sucesso!']);
    }

    public function edit($id)
    {
        $item = Cofre::where('id', $id)->first();

        return view('admin.cofre.edit', [
            'item' => $item
        ]);
    }

    public function update(CofreRequest $request, $id)
    {
        $item = Cofre::where('id', $id)->first();
        $item->fill($request->all());

        if(!empty($request->file('logomarca'))){
            $item->logomarca = $request->file('logomarca')->storeAs(env('AWS_PASTA') . 'cofre', Str::slug($request->name)  . '-' . str_replace('.', '', microtime(true)) . '.' . $request->file('logomarca')->extension());
            $item->save();
        }

        $item->save();

        return Redirect::route('cofre.edit', [
            'id' => $item->id,
        ])->with(['color' => 'success', 'message' => 'Item atualizado com sucesso!']);
    }

    public function itemSetStatus(Request $request)
    {        
        $item = Cofre::find($request->id);
        $item->status = $request->status;
        $item->save();
        return response()->json(['success' => true]);
    }

    public function delete(Request $request)
    {
        $item = Cofre::where('id', $request->id)->first();
        $nome = \App\Helpers\Renato::getPrimeiroNome(Auth::user()->name);

        if(!empty($item)){
            $json = "<b>$nome</b> você tem certeza que deseja excluir este Item?";                      
            return response()->json(['error' => $json,'id' => $request->id]);
        }else{
            return response()->json(['error' => 'Erro ao excluir']);
        }     
    }

    public function deleteon(Request $request)
    {
        $item = Cofre::where('id', $request->item_id)->first();
        if(!empty($item)){
            Storage::delete($item->logomarca);
            $item->delete();
        }
        return Redirect::route('cofre.index')->with([
            'color' => 'success', 
            'message' => 'Item removido com sucesso!'
        ]);
    }

    public function setTxt()
    {
        $items = Cofre::orderBy('created_at', 'DESC')->available()->get();

        $headers = [
            'Content-Type' => 'application/plain',
            'Content-Description' => 'File name',
        ];
        
        $contents = "######################################################################################\n";
        $contents .= "--------------------------------------------------------------------------------------\n";
        $contents .= "############################### INFORMÁTICA LIVRE ####################################\n\n";
        
        if(!empty($items) && $items->count() > 0){
            foreach($items as $item){
                $contents .= "{$item->name}\n";
                $contents .= "Login: {$item->login}\n";
                $contents .= "Email: {$item->password}\n";
                $contents .= "Password: {$item->password}\n";                
                if($item->token){
                    $contents .= "Token: {$item->token}\n";
                }
                if($item->content){
                    $contents .= "Notas Adicionais: {$item->token}\n";
                }
                $contents .= "\n\n";
            }
        }        
        
        return Response::make($contents, 200, $headers);
    }
}
