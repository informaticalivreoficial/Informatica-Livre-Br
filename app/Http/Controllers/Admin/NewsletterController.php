<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NewsletterCatRequest;
use App\Http\Requests\Admin\NewsletterRequest;
use App\Models\Newsletter;
use App\Models\NewsletterCat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class NewsletterController extends Controller
{
    public function listas()
    {
        $listas = NewsletterCat::orderBy('created_at', 'DESC')->paginate(25);

        return view('admin.newsletters.listas',[
            'listas' => $listas
        ]);
    }

    public function listasCreate()
    {
        return view('admin.newsletters.listas-create');
    }

    public function listasStore(NewsletterCatRequest $request)
    {
        $listaCreate  = NewsletterCat::create($request->all());

        return Redirect::route('listas.edit', [
            'id' => $listaCreate->id
        ])->with([
            'color' => 'success',
            'message' => 'Lista cadastrada com sucesso!'
        ]);
    }

    public function listasEdit($id)
    {
        $lista = NewsletterCat::where('id', $id)->first();

        return view('admin.newsletters.listas-edit',[
            'lista' => $lista
        ]);
    }

    public function listasUpdate(NewsletterCatRequest $request, $id)
    {
        $listaUpdate = NewsletterCat::where('id', $id)->first();
        $listaUpdate->fill($request->all());
        $listaUpdate->save();

        return Redirect::route('listas.edit', [
            'id' => $listaUpdate->id
        ])->with([
            'color' => 'success',
            'message' => 'Lista cadastrada com sucesso!'
        ]);
    }

    public function listaSetStatus(Request $request)
    {        
        $lista = NewsletterCat::find($request->id);
        $lista->status = $request->status;
        $lista->save();

        return response()->json(['success' => true]);
    }

    public function listaDelete(Request $request)
    {
        $lista = NewsletterCat::where('id', $request->id)->first();
        $nome = getPrimeiroNome(auth()->user()->name);

        if(!empty($lista)){
            if($lista->newsletters->count() > 0){
                $json = "<b>$nome</b> você tem certeza que deseja excluir esta Lista de Emails?<br> Ela possui {$lista->newsletters->count()} emails cadastrados e todos serão excluídos!";
                return response()->json(['error' => $json,'id' => $lista->id]);
            }else{
                $json = "<b>$nome</b> você tem certeza que deseja excluir esta Lista de Emails?";
                return response()->json(['error' => $json,'id' => $lista->id]);
            }            
        }else{
            return response()->json(['success' => true]);
        }
    }
    
    public function listaDeleteon(Request $request)
    { 
        $lista = NewsletterCat::where('id', $request->lista_id)->first();  
        $listaR = $lista->titulo;
        
        if(!empty($lista)){
            $lista->delete();
        }

        return Redirect::route('listas')->with([
            'color' => 'success', 
            'message' => 'A Lista '.$listaR.' foi removida com sucesso!'
        ]);
    }

    public function newsletters($categoria)
    {
        $lista = NewsletterCat::where('id', $categoria)->first();
        $newsletters = Newsletter::orderBy('created_at', 'Desc')->where('categoria', $categoria)->paginate(35);

        return view('admin.newsletters.newsletters',[
            'emails' => $newsletters,
            'lista' => $lista
        ]);
    }

    public function newsletterCreate()
    {
        $listas = NewsletterCat::orderBy('created_at', 'DESC')->available()->get();
        return view('admin.newsletters.newsletters-create', [
            'listas' => $listas
        ]);
    }

    public function newsletterStore(NewsletterRequest $request)
    {
        $emailCreate = Newsletter::create($request->all());
        
        return Redirect::route('listas.newsletter.edit', [
            'id' => $emailCreate->id 
        ])->with([
            'color' => 'success', 
            'message' => 'O email foi cadastrado com sucesso!'
        ]);
    }

    public function newsletterEdit($id)
    {
        $email = Newsletter::where('id', $id)->first();
        $listas = NewsletterCat::orderBy('created_at', 'DESC')->available()->get();
        
        return view('admin.newsletters.newsletters-edit',[
            'email' => $email,
            'listas' => $listas
        ]);
    }

    public function newsletterUpdate(NewsletterRequest $request, $id)
    {
        $newsletterUpdate = Newsletter::where('id', $id)->first();
        $newsletterUpdate->fill($request->all());
        $newsletterUpdate->save();
        
        return Redirect::route('listas.newsletter.edit', [
            'id' => $newsletterUpdate->id 
        ])->with([
            'color' => 'success', 
            'message' => 'A Inscrição de '.$newsletterUpdate->nome.' foi alualizada com sucesso!'
        ]);
    }
}