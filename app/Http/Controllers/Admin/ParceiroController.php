<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Parceiro as ParceiroRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Cidades;
use App\Models\Estados;
use Illuminate\Http\Request;
use App\Models\Parceiro;
use App\Models\ParceiroGb;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Image;
use App\Support\Cropper;
use Illuminate\Support\Facades\Redirect;

class ParceiroController extends Controller
{
    public function index()
    {
        $parceiros = Parceiro::orderBy('created_at', 'DESC')->paginate(25);
        return view('admin.parceiros.index',[
            'parceiros' => $parceiros
        ]);
    }

    public function create()
    {
        $estados = Estados::orderBy('estado_nome', 'ASC')->get();
        $cidades = Cidades::orderBy('cidade_nome', 'ASC')->get();
        return view('admin.parceiros.create',[
            'estados' => $estados,
            'cidades' => $cidades
        ]);
    }

    public function store(ParceiroRequest $request)
    {
        $parceiroCreate = Parceiro::create($request->all());
        $parceiroCreate->fill($request->all());

        if(!empty($request->hasFile('logomarca'))){
            $parceiroCreate->logomarca = $request->file('logomarca')->storeAs('parceiros', 
            Str::slug($request->name)  . '-' . str_replace('.',
             '', microtime(true)) . '.' . $request->file('logomarca')->extension());
        }

        $parceiroCreate->setSlug();
        
        $validator = Validator::make($request->only('files'), ['files.*' => 'image']);

        if ($validator->fails() === true) {
            return Redirect::back()->withInput()->with([
                'color' => 'orange',
                'message' => 'Todas as imagens devem ser do tipo jpg, jpeg ou png.',
            ]);
        }

        if (isset($request->allFiles()['files']) && $request->allFiles()) {
            foreach ($request->allFiles()['files'] as $image) {
                $parceiroGb = new ParceiroGb();
                $parceiroGb->parceiro_id = $parceiroCreate->id;
                $parceiroGb->path = $image->storeAs('parceiros/' . $parceiroCreate->id, Str::slug($request->name) . '-' . str_replace('.', '', microtime(true)) . '.' . $image->extension());
                $parceiroGb->save();
                unset($parceiroGb);
            }
        }
        
        return Redirect::route('parceiros.edit', $parceiroCreate->id)->with([
            'color' => 'success', 
            'message' => 'Parceiro cadastrado com sucesso!'
        ]);        
    }

    public function edit($id)
    {
        $estados = Estados::orderBy('estado_nome', 'ASC')->get();
        $cidades = Cidades::orderBy('cidade_nome', 'ASC')->get();
        $parceiro = Parceiro::where('id', $id)->first();    
        return view('admin.parceiros.edit', [
            'parceiro' => $parceiro,
            'estados' => $estados,
            'cidades' => $cidades
        ]);
    }

    public function update(ParceiroRequest $request, $id)
    {     
        $parceiro = Parceiro::where('id', $id)->first();        

        if(!empty($request->hasFile('logomarca'))){
            Storage::delete($parceiro->logomarca);
            Cropper::flush($parceiro->logomarca);
            $parceiro->logomarca = '';
        }

        $parceiro->fill($request->all());

        if(!empty($request->hasFile('logomarca'))){
            $parceiro->logomarca = $request->file('logomarca')->storeAs('parceiros', 
            Str::slug($request->name)  . '-' . str_replace('.',
             '', microtime(true)) . '.' . $request->file('logomarca')->extension());
        }

        $parceiro->save();
        $parceiro->setSlug();

        $validator = Validator::make($request->only('files'), ['files.*' => 'image']);

        if ($validator->fails() === true) {
            return Redirect::back()->withInput()->with([
                'color' => 'orange',
                'message' => 'Todas as imagens devem ser do tipo jpg, jpeg ou png.',
            ]);
        }

        if (isset($request->allFiles()['files']) && $request->allFiles()) {
            foreach ($request->allFiles()['files'] as $image) {
                $parceiroImage = new ParceiroGb();
                $parceiroImage->parceiro_id = $parceiro->id;
                $parceiroImage->path = $image->storeAs('parceiros/' . $parceiro->id, Str::slug($request->name) . '-' . str_replace('.', '', microtime(true)) . '.' . $image->extension());
                $parceiroImage->save();
                unset($parceiroImage);
            }
        }

        return Redirect::route('parceiros.edit', [
            'id' => $parceiro->id,
        ])->with(['color' => 'success', 'message' => 'Parceiro atualizado com sucesso!']);
    } 

    public function parceiroSetStatus(Request $request)
    {        
        $parceiro = Parceiro::find($request->id);
        $parceiro->status = $request->status;
        $parceiro->save();
        return response()->json(['success' => true]);
    }

    public function fetchCity(Request $request)
    {
        $data['cidades'] = Cidades::where("estado_id",$request->estado_id)->get(["cidade_nome", "cidade_id"]);
        return response()->json($data);
    }

    public function imageSetCover(Request $request)
    {
        $imageSetCover = ParceiroGb::where('id', $request->image)->first();
        $allImage = ParceiroGb::where('parceiro_id', $imageSetCover->parceiro_id)->get();

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
        $imageDelete = ParceiroGb::where('id', $request->image)->first();

        Storage::delete($imageDelete->path);
        Cropper::flush($imageDelete->path);
        $imageDelete->delete();

        $json = [
            'success' => true,
        ];
        return response()->json($json);
    }

    public function delete(Request $request)
    {
        $parceirodelete = Parceiro::where('id', $request->id)->first();
        $parceiroGb = ParceiroGb::where('parceiro_id', $parceirodelete->id)->first();
        $nome = \App\Helpers\Renato::getPrimeiroNome(Auth::user()->name);

        if(!empty($parceirodelete)){
            if(!empty($parceiroGb)){
                $json = "<b>$nome</b> você tem certeza que deseja excluir este parceiro? Existem imagens adicionadas e todas serão excluídas!";
                return response()->json(['error' => $json,'id' => $parceirodelete->id]);
            }else{
                $json = "<b>$nome</b> você tem certeza que deseja excluir este parceiro?";
                return response()->json(['error' => $json,'id' => $parceirodelete->id]);
            }            
        }else{
            return response()->json(['error' => 'Erro ao excluir']);
        }
    }
    
    public function deleteon(Request $request)
    {
        $parceirodelete = Parceiro::where('id', $request->parceiro_id)->first();  
        $imageDelete = ParceiroGb::where('parceiro_id', $parceirodelete->id)->first();
        $postR = $parceirodelete->name;

        if(!empty($parceirodelete)){
            //Remove Imagens
            if(!empty($imageDelete)){
                Storage::delete($imageDelete->path);
                Cropper::flush($imageDelete->path);
                $imageDelete->delete();
                Storage::deleteDirectory('parceiros/'.$parceirodelete->id);
                $parceirodelete->delete();
            }
            //Remove Logomarca
            Storage::delete($parceirodelete->logomarca);
            Cropper::flush($parceirodelete->logomarca);
            $parceirodelete->delete();
        }

        return Redirect::route('parceiros.index')->with([
            'color' => 'success', 
            'message' => 'O parceiro '.$postR.' foi removido com sucesso!'
        ]);
    }
}
