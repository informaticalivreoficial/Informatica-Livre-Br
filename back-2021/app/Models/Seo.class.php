<?php

/**
 * Seo [ MODEL ]
 * Classe de apoio para o modelo LINK. Pode ser utilizada para gerar SSEO para as páginas do sistema!
 * 
 * @copyright (c) 2014, Robson V. Leite UPINSIDE TECNOLOGIA
 */
class Seo {

    private $File;
    private $Link;
    private $Data;
    private $Tags;
    private $Loc;

    /* DADOS POVOADOS */
    private $seoTags;
    private $seoData;

    function __construct($File, $Link, $Loc) {
        $this->File = strip_tags(trim($File));
        $this->Link = strip_tags(trim($Link));
        $this->Loc = $Loc;
    }

    /**
     * <b>Obter MetaTags:</b> Execute este método informando os valores de navegação para que o mesmo obtenha
     * todas as metas como title, description, og, itemgroup, etc.
     * 
     * <b>Deve ser usada com um ECHO dentro da tag HEAD!</b>
     * @return HTML TAGS =  Retorna todas as tags HEAD
     */
    public function getTags() {
        $this->checkData();
        return $this->seoTags;
    }

    /**
     * <b>Obter Dados:</b> Este será automaticamente povoado com valores de uma tabela single para arquivos
     * como categoria, artigo, etc. Basta usar um extract para obter as variáveis da tabela!
     * 
     * @return ARRAY = Dados da tabela
     */
    public function getData() {
        $this->checkData();
        return $this->seoData;
    }

    /*
     * ***************************************
     * **********  PRIVATE METHODS  **********
     * ***************************************
     */

    //Verifica o resultset povoando os atributos
    private function checkData() {
        if (!$this->seoData):
            $this->getSeo();
        endif;
    }

    //Identifica o arquivo e monta o SEO de acordo
    private function getSeo() {
        $ReadSeo = new Read;

        switch ($this->File):
            
            //SEO:: IMOVEIS
            case 'imoveis':
                $Admin = (isset($_SESSION['userlogin']['nivel']) && $_SESSION['userlogin']['nivel'] == 3 ? true : false);
                $Check = ($Admin ? '' : 'status = 1 AND');

                if($this->Loc[1] == 'categoria'):
                    $nomeCategoria = $this->Loc[2];
                    $readCat = new Read;
                    $readCat->ExeRead("cat_imoveis","WHERE url = :nomeCat","nomeCat={$nomeCategoria}");
                    if($readCat->getResult()):
                        $nomeCat = $readCat->getResult()['0'];
                        $ReadSeo->ExeRead("imoveis", "WHERE {$Check} categoria = :cat", "cat={$nomeCat['id']}");
                        if (!$ReadSeo->getResult()):
                            $this->seoData = null;
                            $this->seoTags = null;
                        else:                            
                            $extract = extract($ReadSeo->getResult()[0]);
                            $this->seoData = $nomeCat['url'];
                            $ArrUpdate = ['visitas' => $nomeCat['visitas'] + 1];
                            $Update = new Update();
                            $Update->ExeUpdate("cat_imoveis", $ArrUpdate, "WHERE id= :catid", "catid={$nomeCat['id']}");
                            $this->Data = ['Imóveis - Categoria '. $nomeCat['nome'] .' - ' . SITENAME, $nomeCat['content'], BASE . "/imoveis/categoria/{$nomeCat['url']}", PATCH . '/images/site.png'];                            
                        endif;
                    endif;                    
                elseif($this->Loc[1] == 'index'):
                    $ReadSeo->ExeRead("imoveis", "WHERE {$Check} status = '1'");
                    if (!$ReadSeo->getResult()):
                        $this->seoData = null;
                        $this->seoTags = null;
                    else:
                        $extract = extract($ReadSeo->getResult()[0]);
                        $this->seoData = $ReadSeo->getResult()[0];
                        $this->Data = ['Imóveis - ' . SITENAME, SITEDESC, BASE . "/imoveis/index", PATCH . '/images/site.jpg'];                        
                    endif;
                elseif($this->Loc[1] == 'busca-avancada'):
                    $this->Data = ['Busca - ' . SITENAME, SITEDESC, BASE . "/imoveis/busca-avancada", PATCH . '/images/site.jpg'];
                elseif($this->Loc[1] == 'cadastrar'):
                    $this->Data = ['Cadastrar seu Imóvel - ' . SITENAME, SITEDESC, BASE . "/imoveis/cadastrar", PATCH . '/images/site.jpg'];
                elseif($this->Loc[1] == 'busca-por-referencia'):
                    $this->Data = ['Buscar imóvel por referência - ' . SITENAME, SITEDESC, BASE . "/imoveis/busca-por-referencia", PATCH . '/images/site.jpg'];
                else:
                    $ReadSeo->ExeRead("imoveis", "WHERE {$Check} url = :link AND status = '1'", "link={$this->Loc[2]}");
                    if (!$ReadSeo->getResult()):
                        $this->seoData = null;
                        $this->seoTags = null;
                    else:
                        $extract = extract($ReadSeo->getResult()[0]);
                        $this->seoData = $ReadSeo->getResult()[0];
                        $this->Data = [$nome . ' - ' . SITENAME, $descricao, BASE . "/imoveis/imovel/{$url}", BASE . "/uploads/{$img}"];

                        //post:: conta views do post
                        $ArrUpdate = ['visitas' => $visitas + 1];
                        $Update = new Update();
                        $Update->ExeUpdate("imoveis", $ArrUpdate, "WHERE id = :postid", "postid={$id}");
                    endif;
                endif;               
                
            break;
            
            //SEO:: BLOG
            case 'blog':
                $Admin = (isset($_SESSION['userlogin']['nivel']) && $_SESSION['userlogin']['nivel'] == 3 ? true : false);
                $Check = ($Admin ? '' : 'status = 1 AND');

                if($this->Loc[1] == 'categoria'):
                    $nomeCategoria = $this->Loc[2];
                    $readCat = new Read;
                    $readCat->ExeRead("cat_posts","WHERE url = :nomeCat","nomeCat={$nomeCategoria}");
                    if($readCat->getResult()):
                        foreach($readCat->getResult() as $nomeCat);
                        $ReadSeo->ExeRead("posts", "WHERE {$Check} categoria = :cat AND tipo = 'artigo'", "cat={$nomeCat['id']}");
                        if (!$ReadSeo->getResult()):
                            $this->seoData = null;
                            $this->seoTags = null;
                        else:                            
                            $extract = extract($ReadSeo->getResult()[0]);
                            $this->seoData = $nomeCat['url'];
                            $ArrUpdate = ['visitas' => $nomeCat['visitas'] + 1];
                            $Update = new Update();
                            $Update->ExeUpdate("cat_posts", $ArrUpdate, "WHERE id= :catid", "catid={$nomeCat['id']}");
                            $this->Data = ['Blog - Categoria '. $nomeCat['nome'] .' - ' . SITENAME, $nomeCat['content'], BASE . "/blog/categoria/{$nomeCat['url']}", PATCH . '/images/site.png'];                            
                        endif;
                    endif;
                    
                elseif($this->Loc[1] == 'artigos'):
                
                elseif($this->Loc[1] == 'pesquisa'):
                
                else:
                    $ReadSeo->ExeRead("posts", "WHERE {$Check} url = :link AND tipo = 'artigo'", "link={$this->Loc[2]}");
                    if (!$ReadSeo->getResult()):
                        $this->seoData = null;
                        $this->seoTags = null;
                    else:
                        $extract = extract($ReadSeo->getResult()[0]);
                        $this->seoData = $ReadSeo->getResult()[0];
                        $this->Data = [$titulo . ' - ' . SITENAME, $content, BASE . "/blog/artigo/{$url}", BASE . "/uploads/artigos/{$thumb}"];

                        //post:: conta views do post
                        $ArrUpdate = ['visitas' => $visitas + 1];
                        $Update = new Update();
                        $Update->ExeUpdate("posts", $ArrUpdate, "WHERE id = :postid", "postid={$id}");
                    endif;
                endif;               
                
            break;
        
            //SEO:: NOTÍCIA
            case 'noticia':
                $Admin = (isset($_SESSION['userlogin']['nivel']) && $_SESSION['userlogin']['nivel'] == 3 ? true : false);
                $Check = ($Admin ? '' : 'status = 1 AND');

                $ReadSeo->ExeRead("posts", "WHERE {$Check} url = :link AND tipo = 'noticia'", "link={$this->Link}");
                if (!$ReadSeo->getResult()):
                    $this->seoData = null;
                    $this->seoTags = null;
                else:
                    $extract = extract($ReadSeo->getResult()[0]);
                    $this->seoData = $ReadSeo->getResult()[0];
                    $this->Data = [$titulo . ' - ' . SITENAME, $content, BASE . "/noticia/{$url}", BASE . "/uploads/noticias/{$thumb}"];

                    //post:: conta views do post
                    $ArrUpdate = ['visitas' => $visitas + 1];
                    $Update = new Update();
                    $Update->ExeUpdate("posts", $ArrUpdate, "WHERE id = :postid", "postid={$id}");
                endif;
                break;
            //SEO:: NOTÍCIA
            case 'categoria':
                $Admin = (isset($_SESSION['userlogin']['nivel']) && $_SESSION['userlogin']['nivel'] == 3 ? true : false);
                $Check = ($Admin ? '' : 'exibir = 1 AND');

                $ReadSeo->ExeRead("cat_posts", "WHERE {$Check} url = :link AND tipo = 'noticia'", "link={$this->Link}");
                if (!$ReadSeo->getResult()):
                    $this->seoData = null;
                    $this->seoTags = null;
                else:
                    $extract = extract($ReadSeo->getResult()[0]);
                    $this->seoData = $ReadSeo->getResult()[0];
                    $this->Data = [$nome . ' - ' . SITENAME, $content, BASE . "/noticia/{$url}", PATCH . '/images/site.png'];

                    //post:: conta views do post
                    //$ArrUpdate = ['visitas' => $visitas + 1];
                    //$Update = new Update();
                    //$Update->ExeUpdate("posts", $ArrUpdate, "WHERE id = :postid", "postid={$id}");
                endif;
            break;
                
            //SEO:: PÁGINA
            case 'sessao':
                $Admin = (isset($_SESSION['userlogin']['nivel']) && $_SESSION['userlogin']['nivel'] == 3 ? true : false);
                $Check = ($Admin ? '' : 'status = 1 AND');
                
                $ReadSeo->ExeRead("posts", "WHERE {$Check} url = :link AND tipo = 'pagina'", "link={$this->Link}");
                if (!$ReadSeo->getResult()):
                    $this->seoData = null;
                    $this->seoTags = null;
                else:
                    $extract = extract($ReadSeo->getResult()[0]);
                    $this->seoData = $ReadSeo->getResult()[0];
                    $this->Data = [$titulo . ' - ' . SITENAME, $content, BASE . "/sessao/{$url}", BASE . "/uploads/paginas/{$thumb}"];

                    //post:: conta views do post
                    $ArrUpdate = ['visitas' => $visitas + 1];
                    $Update = new Update();
                    $Update->ExeUpdate("posts", $ArrUpdate, "WHERE id = :postid", "postid={$id}");
                endif;
            break;
            //SEO:: CATEGORIA
            case 'pagina':
                if ($this->Loc[1] == 'atendimento'):
                    $this->Data = ['Atendimento - ' . SITENAME, '', BASE . "/pagina/atendimento", PATCH . '/images/site.png'];
                elseif($this->Loc[1] == 'simulador'):
                    $this->Data = ['Simular Financiamento - ' . SITENAME, SITEDESC, BASE . "/pagina/simulador", PATCH . '/images/site.jpg'];
                elseif($this->Loc[1] == 'zapchat'):
                    $this->Data = ['Atendimento via WhatsApp - ' . SITENAME, SITEDESC, BASE . "/pagina/zapchat", PATCH . '/images/site.jpg'];
                else:
                    
                endif;
            break;
            
            
            //SEO:: PESQUISA
            case 'pesquisa':
                $ReadSeo->ExeRead("posts", "WHERE status = 1 AND tipo = 'noticia' AND (titulo LIKE '%' :link '%' OR content LIKE '%' :link '%')", "link={$this->Link}");
                if (!$ReadSeo->getResult()):
                    $this->seoData = null;
                    $this->seoTags = null;
                else:
                    $this->seoData['count'] = $ReadSeo->getRowCount();
                    $this->Data = ["Pesquisa por: {$this->Link}" . ' - ' . SITENAME, "Sua pesquisa por {$this->Link} retornou {$this->seoData['count']} resultados!", BASE . "/pesquisa/{$this->Link}", PATCH . '/images/site.png'];
                endif;
            break;

            //SEO:: LISTA EMPRESAS
            case 'empresas':
                $Name = ucwords(str_replace("-", " ", $this->Link));
                $this->seoData = ["empresa_link" => $this->Link, "empresa_cat" => $Name];
                $this->Data = ["Empresas {$this->Link}" . SITENAME, "Confira o guia completo de sua cidade, e encontra empresas {$this->Link}.", BASE . '/empresas/' . $this->Link, PATCH . '/images/site.png'];
                break;

            //SEO:: EMPRESA SINGLE
            case 'empresa':
                $Admin = (isset($_SESSION['userlogin']['user_level']) && $_SESSION['userlogin']['user_level'] == 3 ? true : false);
                $Check = ($Admin ? '' : 'empresa_status = 1 AND');

                $ReadSeo->ExeRead("app_empresas", "WHERE {$Check} empresa_name = :link", "link={$this->Link}");
                if (!$ReadSeo->getResult()):
                    $this->seoData = null;
                    $this->seoTags = null;
                else:
                    extract($ReadSeo->getResult()[0]);
                    $this->seoData = $ReadSeo->getResult()[0];
                    $this->Data = [$empresa_title . ' - ' . SITENAME, $empresa_sobre, BASE . "/empresa/{$empresa_name}", BASE . "/uploads/{$empresa_capa}"];

                    //empresa:: conta views da empresa
                    $ArrUpdate = ['empresa_views' => $empresa_views + 1];
                    $Update = new Update();
                    $Update->ExeUpdate("app_empresas", $ArrUpdate, "WHERE empresa_id = :empresaid", "empresaid={$empresa_id}");
                endif;
                break;

            //SEO:: CADASTRA EMPRESA
            case 'cadastra-empresa':
                $this->Data = ["Cadastre sua Empresa - " . SITENAME, "Página modelo para cadastro de empresas via Front-End do curso Work Series - PHP Orientado a Objetos!", BASE . '/cadastra-empresa/' . $this->Link, PATCH . '/images/site.png'];
                break;

            //SEO:: INDEX
            case 'index':
                $this->Data = [SITENAME , SITEDESC, BASE, BASE . '/uploads/logomarca/'.METAIMAGEM];
                break;

            //SEO:: 404
            default :
                $this->Data = [SITENAME . ' - 404 Oppsss, Nada encontrado!', SITEDESC, BASE . '/404', BASE . '/uploads/'.METAIMAGEM];

        endswitch;

        if ($this->Data):
            $this->setTags();
        endif;
    }

    //Monta e limpa as tags para alimentar as tags
    private function setTags() {
        $this->Tags['Title'] = $this->Data[0];
        $this->Tags['Content'] = Check::Words(html_entity_decode($this->Data[1]), 25);
        $this->Tags['Link'] = $this->Data[2];
        $this->Tags['Image'] = $this->Data[3];

        $this->Tags = array_map('strip_tags', $this->Tags);
        $this->Tags = array_map('trim', $this->Tags);

        $this->Data = null;

        //NORMAL PAGE
        $this->seoTags = '<title>' . $this->Tags['Title'] . '</title> ' . "\n";
        $this->seoTags .= '<meta name="description" content="' . $this->Tags['Content'] . '"/>' . "\n";
        $this->seoTags .= '<meta name="robots" content="index, follow" />' . "\n";
        $this->seoTags .= '<link rel="canonical" href="' . $this->Tags['Link'] . '">' . "\n";
        $this->seoTags .= "\n";

        //FACEBOOK
        $this->seoTags .= '<meta property="og:site_name" content="' . SITENAME . '" />' . "\n";
        $this->seoTags .= '<meta property="og:locale" content="pt_BR" />' . "\n";
        $this->seoTags .= '<meta property="og:locale:alternate" content="en_US" />' . "\n";
        $this->seoTags .= '<meta property="og:title" content="' . $this->Tags['Title'] . '" />' . "\n";
        $this->seoTags .= '<meta property="og:description" content="' . $this->Tags['Content'] . '" />' . "\n";
        $this->seoTags .= '<meta property="og:image" content="' . $this->Tags['Image'] . '" />' . "\n";
        $this->seoTags .= '<meta property="og:url" content="' . $this->Tags['Link'] . '" />' . "\n";
        $this->seoTags .= '<meta property="og:type" content="article" />' . "\n";
        $this->seoTags .= '<meta property="og:site_name" content="' . SITENAME . '" />' . "\n";
        $this->seoTags .= "\n";

        //ITEM GROUP (TWITTER)
        $this->seoTags .= '<meta itemprop="name" content="' . $this->Tags['Title'] . '">' . "\n";
        $this->seoTags .= '<meta itemprop="description" content="' . $this->Tags['Content'] . '">' . "\n";
        $this->seoTags .= '<meta itemprop="url" content="' . $this->Tags['Link'] . '">' . "\n";

        $this->Tags = null;
    }

}
