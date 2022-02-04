<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Configuracoes;

use Spatie\Sitemap\SitemapGenerator;
use Carbon\Carbon;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

// use Spatie\Feed\Feedable;
// use Spatie\Feed\FeedItem;

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
            //->add('/destaque')
            ->add('/blog/artigos')
            ->add('/roteiros')
            ->add('/embarcacoes')
            ->writeToFile('sitemap.xml'); 
        
        return response()->json(['success' => true]);
    }

    // public function gerarfeed(Request $request)
    // {
    //     $configupdate = Configuracoes::where('id', $request->id)->first();
    //     $configupdate->rss_data = date('Y-m-d');
    //     $configupdate->save();

    //     return response()->json(['success' => true]);
    // }
}
