<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Configuracoes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClienteController extends Controller
{
    public function login()
    {
        return view('web.cliente.login');
    }

    public function passeios()
    {
        $Configuracoes = Configuracoes::where('id', '1')->first();
        $head = $this->seo->render('Meus Passeios - ' . $Configuracoes->nomedosite ?? 'Informática Livre',
            $Configuracoes->descricao ?? 'Informática Livre desenvolvimento de sistemas web desde 2005',
            route('web.home'),
            Storage::url($Configuracoes->metaimg ?? 'https://informaticalivre.com/media/metaimg.jpg')
        ); 
        return view('web.cliente.meus-passeios',[
            'head' => $head
        ]);
    }
}
