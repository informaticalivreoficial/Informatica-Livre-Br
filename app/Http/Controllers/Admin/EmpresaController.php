<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Empresa as EmpresaRequest;
use Illuminate\Support\Facades\Storage;
use App\Support\Cropper;
use Illuminate\Support\Str;
use App\Models\Cidades;
use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Estados;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class EmpresaController extends Controller
{
    public function index()
    {
        $empresas = Empresa::orderBy('created_at', 'DESC')->orderBy('status', 'ASC')->paginate(25);
        return view('admin.empresas.index', [
            'empresas' => $empresas,
        ]);
    }
    
    public function create()
    {
        $estados = Estados::orderBy('estado_nome', 'ASC')->get();
        $cidades = Cidades::orderBy('cidade_nome', 'ASC')->get();
        $users = User::orderBy('name')->get();
        return view('admin.empresas.create', [
            'users' => $users,
            'estados' => $estados,
            'cidades' => $cidades
        ]);
    }
    
    public function store(EmpresaRequest $request)
    {
        $criarEmpresa = Empresa::create($request->all());
        $criarEmpresa->fill($request->all());

        if(!empty($request->file('logomarca'))){
            $criarEmpresa->logomarca = $request->file('logomarca')->storeAs('empresas', Str::slug($request->alias_name)  . '-' . str_replace('.', '', microtime(true)) . '.' . $request->file('logomarca')->extension());
            $criarEmpresa->save();
        }
        
        return redirect()->route('empresas.edit', [
            'id' => $criarEmpresa->id,
        ])->with(['color' => 'success', 'message' => 'Empresa cadastrada com sucesso!']);
    }
    
    public function edit($id)
    {
        $estados = Estados::orderBy('estado_nome', 'ASC')->get();
        $cidades = Cidades::orderBy('cidade_nome', 'ASC')->get();
        $empresa = Empresa::where('id', $id)->first();
        $users = User::orderBy('name')->get();

        return view('admin.empresas.edit', [
            'empresa' => $empresa,
            'users' => $users,
            'estados' => $estados,
            'cidades' => $cidades
        ]);
    }

    public function fetchCity(Request $request)
    {
        $data['cidades'] = Cidades::where("estado_id",$request->estado_id)->get(["cidade_nome", "cidade_id"]);
        return response()->json($data);
    }
   
    public function update(EmpresaRequest $request, $id)
    {
        $empresa = Empresa::where('id', $id)->first();
        $empresa->fill($request->all());

        if(!empty($request->file('logomarca'))){
            $empresa->logomarca = $request->file('logomarca')->storeAs('empresas', Str::slug($request->alias_name)  . '-' . str_replace('.', '', microtime(true)) . '.' . $request->file('logomarca')->extension());
            $empresa->save();
        }

        $empresa->save();

        return redirect()->route('empresas.edit', [
            'id' => $empresa->id,
        ])->with(['color' => 'success', 'message' => 'Empresa atualizada com sucesso!']);
    }

    public function empresaSetStatus(Request $request)
    {        
        $empresa = Empresa::find($request->id);
        $empresa->status = $request->status;
        $empresa->save();
        return response()->json(['success' => true]);
    }

    public function delete(Request $request)
    {
        $empresa = Empresa::where('id', $request->id)->first();
        $nome = getPrimeiroNome(Auth::user()->name);

        if(!empty($empresa)){
            $json = "<b>$nome</b> você tem certeza que deseja excluir esta empresa?";                      
            return response()->json(['error' => $json,'id' => $request->id]);
        }else{
            return response()->json(['error' => 'Erro ao excluir']);
        }     
    }

    public function deleteon(Request $request)
    {
        $empresa = Empresa::where('id', $request->empresa_id)->first();
        if(!empty($empresa)){
            Storage::delete($empresa->logomarca);
            Cropper::flush($empresa->logomarca);
            $empresa->delete();
        }
        return redirect()->route('admin.empresas.index')->with([
            'color' => 'success', 
            'message' => 'Empresa removida com sucesso!'
        ]);
    }
}
