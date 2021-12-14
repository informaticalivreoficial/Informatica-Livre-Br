<?php
/**
 * AdminConfig.class [ MODEL ADMIN ]
 * Respnsável por gerenciar as configurações do sistema!
 * 
 * @copyright (c) 2018, Renato Montanari Informática Livre
 */
 
class AdminConfig {
    
    private $Data;
    private $Config;
    private $Result;
    private $Error;
    
    //Nome da tabela no banco de dados
    const Entity = 'configuracoes';
    
    /**
     * <b>Cadastrar Configuração:</b> Envelope os dados de uma configuração em um array atribuitivo 
     * e execute esse método para cadastrar o mesmo no sistema. Validações serão feitas!
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeCreate(array $Data){
        
    }    
    
    /**
     * <b>Atualizar Configuração:</b> Envelope os dados em uma array atribuitivo e informe o id de uma
     * configuração para atualiza-la no sistema!
     * @param INT $ConfigId = Id da configuração
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeUpdate($ConfigId, array $Data) {
        $this->Config = (int) $ConfigId;        
        $this->Data = $Data;
        $this->setData();        
        $this->checkData();
        
        if(isset($this->Data['logomarca'])):            
            if(is_array($this->Data['logomarca'])):
            $readCapa = new Read;
            $readCapa->ExeRead(self::Entity, "WHERE id = :post", "post={$this->Config}");
            $capa = '../uploads/' . $readCapa->getResult()[0]['logomarca'];
            if (file_exists($capa) && !is_dir($capa)):
                unlink($capa);
            endif;     
            $uploadCapa1 = new Upload;
            $uploadCapa1->ImageSemData($this->Data['logomarca'], 'logomarca-'.Check::Name($this->Data['nomedosite']), $this->Data['logomarca_width'], 'logomarca');
            $this->Data['logomarca_width'] = $this->Data['logomarca_width'];
            endif;          
        elseif(isset($this->Data['logomarcaadmin'])):
            if(is_array($this->Data['logomarcaadmin'])):
            $readCapa = new Read;
            $readCapa->ExeRead(self::Entity, "WHERE id = :post", "post={$this->Config}");
            $capa = '../uploads/' . $readCapa->getResult()[0]['logomarcaadmin'];
            if (file_exists($capa) && !is_dir($capa)):
                unlink($capa);
            endif;     
            $uploadCapa2 = new Upload;
            $uploadCapa2->ImageSemData($this->Data['logomarcaadmin'], 'logomarcaAdmin-'.Check::Name($this->Data['nomedosite']), $this->Data['logomarcaadmin_width'], 'logomarca');
            $this->Data['logomarcaadmin_width'] = $this->Data['logomarcaadmin_width'];
            endif; 
        elseif(isset($this->Data['favicon'])):
            if(is_array($this->Data['favicon'])):
            $readCapa = new Read;
            $readCapa->ExeRead(self::Entity, "WHERE id = :post", "post={$this->Config}");
            $capa = '../uploads/' . $readCapa->getResult()[0]['favicon'];
            if (file_exists($capa) && !is_dir($capa)):
                unlink($capa);
            endif;     
            $uploadCapa3 = new Upload;
            $uploadCapa3->ImageSemData($this->Data['favicon'], 'logomarcaFavicon-'.Check::Name($this->Data['nomedosite']), $this->Data['favicon_width'], 'logomarca');
            $this->Data['favicon_width'] = $this->Data['favicon_width'];
            endif;
        elseif(isset($this->Data['metaimg'])):
            if(is_array($this->Data['metaimg'])):
            $readCapa = new Read;
            $readCapa->ExeRead(self::Entity, "WHERE id = :post", "post={$this->Config}");
            $capa = '../uploads/' . $readCapa->getResult()[0]['metaimg'];
            if (file_exists($capa) && !is_dir($capa)):
                unlink($capa);
            endif;     
            $uploadCapa4 = new Upload;
            $uploadCapa4->ImageSemData($this->Data['metaimg'], 'MetaImg-'.Check::Name($this->Data['nomedosite']), $this->Data['metaimg_width'], 'logomarca');
            $this->Data['metaimg_width'] = $this->Data['metaimg_width'];
            endif;                    
        endif;
        
        if (isset($uploadCapa1) && $uploadCapa1->getResult()):
            $this->Data['logomarca'] = $uploadCapa1->getResult();
            $this->Update();
        elseif(isset($uploadCapa2) && $uploadCapa2->getResult()):
            $this->Data['logomarcaadmin'] = $uploadCapa2->getResult();
            $this->Update();
        elseif(isset($uploadCapa3) && $uploadCapa3->getResult()):
            $this->Data['favicon'] = $uploadCapa3->getResult();
            $this->Update();
        elseif(isset($uploadCapa4) && $uploadCapa4->getResult()):
            $this->Data['metaimg'] = $uploadCapa4->getResult();
            $this->Update();
        else:
            unset($this->Data['logomarca']);
            unset($this->Data['logomarcaadmin']);
            unset($this->Data['favicon']);
            unset($this->Data['metaimg']);
            if(!empty($uploadCapa1) && $uploadCapa1->getError()):
                RMErro("<strong>Erro ao enviar a Capa</strong>: " .$uploadCapa1->getError(), E_USER_WARNING);
            elseif(!empty($uploadCapa2) && $uploadCapa2->getError()):
                RMErro("<strong>Erro ao enviar a Capa</strong>: " .$uploadCapa2->getError(), E_USER_WARNING);
            elseif(!empty($uploadCapa3) && $uploadCapa3->getError()):
                RMErro("<strong>Erro ao enviar a Capa</strong>: " .$uploadCapa3->getError(), E_USER_WARNING);
            elseif(!empty($uploadCapa4) && $uploadCapa4->getError()):
                RMErro("<strong>Erro ao enviar a Capa</strong>: " .$uploadCapa4->getError(), E_USER_WARNING);
            endif;
            $this->Update();
        endif;        
    }
    
    /**
     * <b>Verificar Cadastro:</b> Retorna TRUE se o cadastro ou update for efetuado ou FALSE se não.
     * Para verificar erros execute um getError();
     * @return BOOL $Var = True or False
     */
    public function getResult() {
        return $this->Result;
    }

    /**
     * <b>Obter Erro:</b> Retorna um array associativo com um erro e um tipo.
     * @return ARRAY $Error = Array associatico com o erro
     */
    public function getError() {
        return $this->Error;
    }
    
    /*
     * ***************************************
     * **********  PRIVATE METHODS  **********
     * ***************************************
     */     
    
        
    //Verifica os dados digitados no formulário
    private function checkData() {
        if ($this->Data['nomedosite'] == ''):
            $this->Error = ["<strong>Erro!</strong> Você precisa preecher pelo menos o <strong>nome do seu site</strong>!", RM_ERROR];
            $this->Result = false;       
        endif;
    }
    
    //Valida e cria os dados para realizar o cadastro
    private function setData(){
        if(isset($this->Data['submitxmllogomarca'])):
            $logomarca = $this->Data['logomarca'];
            unset($this->Data['submitxmllogomarca']);
            unset($this->Data['logomarca']);            
            $this->Data = array_map('strip_tags', $this->Data);
            $this->Data = array_map('trim', $this->Data);            
            $this->Data['logomarca'] = $logomarca;
            $this->Data['uppdate']     = date('Y-m-d H:i:s');
        elseif(isset($this->Data['submitxmllogoadmin'])):
            $logomarcaadmin = $this->Data['logomarcaadmin'];
            unset($this->Data['submitxmllogoadmin']);
            unset($this->Data['logomarcaadmin']);            
            $this->Data = array_map('strip_tags', $this->Data);
            $this->Data = array_map('trim', $this->Data);            
            $this->Data['logomarcaadmin'] = $logomarcaadmin;
            $this->Data['uppdate']     = date('Y-m-d H:i:s');
        elseif(isset($this->Data['submitxmlfavicon'])):
            $favicon = $this->Data['favicon'];
            unset($this->Data['submitxmlfavicon']);
            unset($this->Data['favicon']);            
            $this->Data = array_map('strip_tags', $this->Data);
            $this->Data = array_map('trim', $this->Data);            
            $this->Data['favicon'] = $favicon;
            $this->Data['uppdate']     = date('Y-m-d H:i:s');
        elseif(isset($this->Data['InfoGerais'])):
            $Metaimg = $this->Data['metaimg'];
            unset($this->Data['InfoGerais']);
            unset($this->Data['metaimg']);            
            $this->Data = array_map('strip_tags', $this->Data);
            $this->Data = array_map('trim', $this->Data);            
            $this->Data['metaimg'] = $Metaimg;
            $this->Data['uppdate']     = date('Y-m-d H:i:s');
        elseif(isset($this->Data['submitseo'])):
            unset($this->Data['submitseo']);
            $this->Data = array_map('strip_tags', $this->Data);
            $this->Data = array_map('trim', $this->Data);            
            $this->Data['sitemapdata'] = date('Y-m-d');
            $this->Data['sitemap']     = BASE.'/sitemap.xml'; 
            $this->Data['uppdate']     = date('Y-m-d H:i:s');  
        elseif(isset($this->Data['submitrss'])):
            unset($this->Data['submitrss']);
            $this->Data = array_map('strip_tags', $this->Data);
            $this->Data = array_map('trim', $this->Data);            
            $this->Data['rssdata'] = date('Y-m-d');
            $this->Data['rss']     = BASE.'/rss.xml'; 
            $this->Data['uppdate']     = date('Y-m-d H:i:s');
        else:
            $this->Data = array_map('strip_tags', $this->Data);
            $this->Data = array_map('trim', $this->Data);
        endif;        
    }
    
    //Cadastra Configuração!
    private function Create() {
        $Create = new Create;    
    
        $Create->ExeCreate(self::Entity, $this->Data);
    
        if ($Create->getResult()):
            $this->Error = ["A Configuração <b>{$this->Data['nomedosite']}</b> foi cadastrada com sucesso no sistema!", RM_ACCEPT];
            $this->Result = $Create->getResult();
        endif;        
    }
    
    //Atualiza Configuração!
    private function Update() {
        $Update = new Update;
        $Update->ExeUpdate(self::Entity, $this->Data, "WHERE id = :id", "id={$this->Config}");        
        if ($Update->getResult()):
            $this->Error = ["A Configuração <b>{$this->Data['nomedosite']}</b> foi atualizada com sucesso!", RM_ACCEPT];
            $this->Result = true;
        endif;
    }
}
?>