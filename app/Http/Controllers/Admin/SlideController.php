<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\Slide;
use App\Http\Requests\Admin\Slide as SlideRequest;
use App\Support\Cropper;
use Illuminate\Support\Facades\Redirect;

class SlideController extends Controller
{
    public function index()
    {
        $slides = Slide::orderBy('created_at', 'DESC')->orderBy('status', 'ASC')->paginate(25);
        return view('admin.slides.index', [
            'slides' => $slides,
        ]);
    }
    
    public function create()
    {
        return view('admin.slides.create');
    }
    
    public function store(SlideRequest $request)
    {
        $slideCreate = Slide::create($request->all()); 
        $slideCreate->setSlug();

        $validator = Validator::make($request->only('files'), ['files.*' => 'image']);

        if ($validator->fails() === true) {
            return Redirect::back()->withInput()->with([
                'color' => 'orange',
                'message' => 'Todas as imagens devem ser do tipo jpg, jpeg ou png.',
            ]);
        }

        if(!empty($request->file('imagem'))){
            $slideCreate->imagem = $request->file('imagem')->storeAs('slides', Str::slug($request->titulo)  . '-' . str_replace('.', '', microtime(true)) . '.' . $request->file('imagem')->extension());
            $slideCreate->save();
        }

        return Redirect::route('slides.edit', $slideCreate->id)->with(['color' => 'success', 'message' => 'Slide cadastrado com sucesso!']);
    }    
    
    public function edit($id)
    {
        $slide = Slide::where('id', $id)->first(); 
        
        return view('admin.slides.edit', [
            'slide' => $slide
        ]);
    }
    
    public function update(SlideRequest $request, $id)
    {
        $slide = Slide::where('id', $id)->first();
        
        $validator = Validator::make($request->only('files'), ['files.*' => 'image']);

        if ($validator->fails() === true) {
            return Redirect::back()->withInput()->with([
                'color' => 'orange',
                'message' => 'Todas as imagens devem ser do tipo jpg, jpeg ou png.',
            ]);
        }

        if(!empty($request->file('imagem'))){
            Storage::delete($slide->imagem);
            Cropper::flush($slide->imagem);
            $slide->imagem = '';
        }

        $slide->fill($request->all());
        $slide->setSlug();

        if(!empty($request->file('imagem'))){
            $slide->imagem = $request->file('imagem')->storeAs('slides', Str::slug($request->titulo)  . '-' . str_replace('.', '', microtime(true)) . '.' . $request->file('imagem')->extension());
        }

        if(!$slide->save()){
            return Redirect::back()->withInput()->with([
                'color' => 'orange',
                'message' => 'Erro ao salvar Slide',
            ]);
        }        

        return Redirect::route('slides.edit', $slide->id)->with(['color' => 'success', 'message' => 'Slide atualizado com sucesso!']);
    }

    public function slideSetStatus(Request $request)
    {        
        $slide = Slide::find($request->id);
        $slide->status = $request->status;
        $slide->save();
        return response()->json(['success' => true]);
    }

    public function delete(Request $request)
    {
        $slide = Slide::where('id', $request->id)->first();
        $nome = \App\Helpers\Renato::getPrimeiroNome(Auth::user()->name);
        if(!empty($slide)){
            $json = "<b>$nome</b> você tem certeza que deseja excluir este Slide?";
            return response()->json(['error' => $json,'id' => $slide->id]);
        }else{
            return response()->json(['success' => true]);
        }
    }
    
    public function deleteon(Request $request)
    { 
        $slide = Slide::where('id', $request->slide_id)->first();  
        $slideR = $slide->titulo;
        if(!empty($slide)){
            Storage::delete($slide->imagem);
            Cropper::flush($slide->imagem);
            $slide->delete();
        }
        return Redirect::route('slides.index')->with(['color' => 'success', 'message' => 'O slide '.$slideR.' foi removido com sucesso!']);
    }
}
