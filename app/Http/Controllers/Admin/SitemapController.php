<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Configuracoes;

use Spatie\Sitemap\SitemapGenerator;
use Carbon\Carbon;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{  
        
    public function gerarxml(Request $request)
    {
        $configupdate = Configuracoes::where('id', $request->id)->first();
        $configupdate->sitemap_data = date('Y-m-d');
        $configupdate->sitemap = url('/sitemap.xml');
        $configupdate->save();

        Sitemap::create()->add(Url::create('/atendimento')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            ->setPriority(0.1))
            ->add('/')
            ->add('/blog/artigos')
            ->add('/quem-somos')
            ->add('/portifolio')
            ->add('/consultoria/produtos')
            ->add('/consultoria/orcamento')            
            ->writeToFile('sitemap.xml'); 
        
        return response()->json(['success' => true]);
    }
}
