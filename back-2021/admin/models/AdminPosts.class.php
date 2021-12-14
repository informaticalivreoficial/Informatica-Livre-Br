<?php

/**
 * AdminPost.class [ MODEL ADMIN ]
 * Respnsável por gerenciar os posts no Admin do sistema!
 * 
 * @copyright (c) 2014, Robson V. Leite UPINSIDE TECNOLOGIA
 */
class AdminPost {

    private $Data;
    private $Post;
    private $Error;
    private $Result;

    //Nome da tabela no banco de dados
    const Entity = 'posts';

    /**
     * <b>Cadastrar o Post:</b> Envelope os dados do post em um array atribuitivo e execute esse método
     * para cadastrar o post. Envia a capa automaticamente!
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeCreate(array $Data) {
        $this->Data = $Data;

        if ($this->Data['titulo'] == ''):
            $this->Error = ["Erro ao cadastrar: Por favor preencha o campo <strong>Título</strong>!", RM_ERROR];
            $this->Result = false;
        elseif($this->Data['categoria'] == ''):
            $this->Error = ["Erro ao cadastrar: Por favor selecione uma <strong>Categoria</strong>!", RM_ERROR];
            $this->Result = false;
        else:
            $this->setData();
            $this->setName();

            if ($this->Data['thumb']): 
            
                if($this->Data['tipo'] == 'noticia'):
                    $pasta 	= 'noticias';                    
                elseif($this->Data['tipo'] == 'artigo'):
                    $pasta 	= 'artigos';
                else:
                    $pasta 	= 'paginas';
                endif;                
                
                $upload = new Upload;
                $upload->Image($this->Data['thumb'], $this->Data['url'], '800', $pasta.'/capas');
            endif;            

            if (isset($upload) && $upload->getResult()):
                $this->Data['thumb'] = $upload->getResult();
                $this->Create();
            else:
                $this->Data['thumb'] = null;
                    $_SESSION['errCapa'] = "<strong>Você não enviou uma Capa</strong> ou o <strong>Tipo de arquivo é inválido</strong>, envie imagens JPG ou PNG!";
                $this->Create();
            endif;           
            
        endif;
    }

    /**
     * <b>Atualizar Post:</b> Envelope os dados em uma array atribuitivo e informe o id de um 
     * post para atualiza-lo na tabela!
     * @param INT $PostId = Id do post
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeUpdate($PostId, array $Data) {
        $this->Post = (int) $PostId;
        $this->Data = $Data;

        if ($this->Data['titulo'] == ''):
            $this->Error = ["Erro ao cadastrar: Por favor preencha o campo <strong>Título</strong>!", RM_ERROR];
            $this->Result = false;
        elseif($this->Data['categoria'] == ''):
            $this->Error = ["Erro ao cadastrar: Por favor selecione uma <strong>Categoria</strong>!", RM_ERROR];
            $this->Result = false;
        else:
            $this->setData();
            $this->setName();
            $this->Data['uppdate'] = date('Y-m-d H:i:s');

            if (is_array($this->Data['thumb'])):
                $readCapa = new Read;
                $readCapa->ExeRead(self::Entity, "WHERE id = :post", "post={$this->Post}");
                $capa = '../uploads/' . $readCapa->getResult()[0]['thumb'];
                if (file_exists($capa) && !is_dir($capa)):
                    unlink($capa);
                endif;
                
                if($this->Data['tipo'] == 'noticia'):
                    $pasta 	= 'noticias';                    
                elseif($this->Data['tipo'] == 'artigo'):
                    $pasta 	= 'artigos';
                else:
                    $pasta 	= 'paginas';
                endif;

                $uploadCapa = new Upload;
                $uploadCapa->Image($this->Data['thumb'], $this->Data['url'], '800', $pasta.'/capas');
                //$uploadCapa->Image($this->Data['post_cover'], $this->Data['post_name']);
            endif; 

            if (isset($uploadCapa) && $uploadCapa->getResult()):
                $this->Data['thumb'] = $uploadCapa->getResult();
                $this->Update();
            else:
                unset($this->Data['thumb']);
                if(!empty($uploadCapa) && $uploadCapa->getError()):
                    RMErro("<strong>Erro ao enviar a Capa</strong>: " .$uploadCapa->getError(), E_USER_WARNING);
                endif;
                $this->Update();
                //var_dump($uploadCapa);
            endif;
        endif;
    }

    /**
     * <b>Deleta Post:</b> Informe o ID do post a ser removido para que esse método realize uma checagem de
     * pastas e galerias excluinto todos os dados nessesários!
     * @param INT $PostId = Id do post
     */
    public function ExeDelete($PostId) {
        $this->Post = (int) $PostId;

        $ReadPost = new Read;
        $ReadPost->ExeRead(self::Entity, "WHERE id = :post", "post={$this->Post}");

        if (!$ReadPost->getResult()):
            $this->Error = ["O post que você tentou deletar não existe no sistema!", RM_ERROR];
            $this->Result = false;
        else:
            $PostDelete = $ReadPost->getResult()[0];
            if (file_exists('../uploads/' . $PostDelete['thumb']) && !is_dir('../uploads/' . $PostDelete['thumb'])):
                unlink('../uploads/' . $PostDelete['thumb']);
            endif;

            $readGallery = new Read;
            $readGallery->ExeRead("posts_gb", "WHERE post_id = :id", "id={$this->Post}");
            if ($readGallery->getResult()):
                foreach ($readGallery->getResult() as $gbdel):
                    if (file_exists('../uploads/' . $gbdel['img']) && !is_dir('../uploads/' . $gbdel['img'])):
                        unlink('../uploads/' . $gbdel['img']);
                    endif;
                endforeach;
            endif;

            $deleta = new Delete;
            $deleta->ExeDelete("posts_gb", "WHERE post_id = :gbpost", "gbpost={$this->Post}");
            $deleta->ExeDelete(self::Entity, "WHERE id = :postid", "postid={$this->Post}");

            $this->Error = ["O post <b>{$PostDelete['titulo']}</b> foi removido com sucesso do sistema!", RM_ACCEPT];
            $this->Result = true;

        endif;
    }

    /**
     * <b>Ativa/Inativa Post:</b> Informe o ID do post e o status e um status sendo 1 para ativo e 0 para
     * rascunho. Esse méto ativa e inativa os posts!
     * @param INT $PostId = Id do post
     * @param STRING $PostStatus = 1 para ativo, 0 para inativo
     */
    public function ExeStatus($PostId, $PostStatus) {
        $this->Post = (int) $PostId;
        $this->Data['status'] = (string) $PostStatus;
        $Update = new Update;
        $Update->ExeUpdate(self::Entity, $this->Data, "WHERE id = :id", "id={$this->Post}");
    }

    /**
     * <b>Enviar Galeria:</b> Envelope um $_FILES de um input multiple e envie junto a um postID para executar
     * o upload e o cadastro de galerias do artigo!
     * @param ARRAY $Files = Envie um $_FILES multiple
     * @param INT $PostId = Informe o ID do post
     */
    public function gbSend(array $Images, $PostId, $Pasta) {
        $this->Post = (int) $PostId;
        $this->Data = $Images;
        $this->Pasta = $Pasta;

        $ImageName = new Read;
        $ImageName->ExeRead(self::Entity, "WHERE id = :id", "id={$this->Post}");

        if (!$ImageName->getResult()):
            $this->Error = ["Erro ao enviar galeria. O índice {$this->Post} não foi encontrado no banco!", RM_ERROR];
            $this->Result = false;
        else:
            $ImageName = $ImageName->getResult()[0]['url'];

            $gbFiles = array();
            $gbCount = count($this->Data['tmp_name']);
            $gbKeys = array_keys($this->Data);

            for ($gb = 0; $gb < $gbCount; $gb++):
                foreach ($gbKeys as $Keys):
                    $gbFiles[$gb][$Keys] = $this->Data[$Keys][$gb];
                endforeach;
            endfor;

            $gbSend = new Upload;
            $i = 0;
            $u = 0;

            foreach ($gbFiles as $gbUpload):
                $i++;
                $ImgName = "{$ImageName}-gb-{$this->Post}-" . (substr(md5(time() + $i), 0, 5));
                $gbSend->Image($gbUpload, $ImgName, null,$this->Pasta);
                //$gbSend->Image(pasta raiz, Nome, Tamanho, Pasta);

                if ($gbSend->getResult()):
                    $gbImage = $gbSend->getResult();
                    list($y, $m) = explode('/', date('Y/m'));
                    $gbCreate = ['post_id' => $this->Post, "img" => $gbImage, "data" => date('Y-m-d H:i:s')];
                    $insertGb = new Create;
                    $insertGb->ExeCreate("posts_gb", $gbCreate);
                    $u++;
                endif;

            endforeach;

            if ($u > 1):
                $this->Error = ["Galeria Atualizada: Foram enviadas {$u} imagens para galeria deste post!", RM_ACCEPT];
                $this->Result = true;
            endif;
        endif;
    }

    /**
     * <b>Deletar Imagem da galeria:</b> Informe apenas o id da imagem na galeria para que esse método leia e remova
     * a imagem da pasta e delete o registro do banco!
     * @param INT $GbImageId = Id da imagem da galleria
     */
    public function gbRemove($GbImageId) {
        $this->Post = (int) $GbImageId;
        $readGb = new Read;
        $readGb->ExeRead("posts_gb", "WHERE id = :gb", "gb={$this->Post}");
        if ($readGb->getResult()):

            $Imagem = '../uploads/' . $readGb->getResult()[0]['img'];

            if (file_exists($Imagem) && !is_dir($Imagem)):
                unlink($Imagem);
            endif;

            $Deleta = new Delete;
            $Deleta->ExeDelete("posts_gb", "WHERE id = :id", "id={$this->Post}");
            if ($Deleta->getResult()):
                $this->Error = ["A imagem foi removida com sucesso da galeria!", RM_ACCEPT];
                $this->Result = true;
            endif;

        endif;
    }

    /**
     * <b>Verificar Cadastro:</b> Retorna ID do registro se o cadastro for efetuado ou FALSE se não.
     * Para verificar erros execute um getError();
     * @return BOOL $Var = InsertID or False
     */
    public function getResult() {
        return $this->Result;
    }

    /**
     * <b>Obter Erro:</b> Retorna um array associativo com uma mensagem e o tipo de erro.
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

    //Valida e cria os dados para realizar o cadastro
    private function setData() {
        $Cover = $this->Data['thumb'];
        $Content = $this->Data['content'];
        unset($this->Data['thumb'], $this->Data['content']);

        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);

        $this->Data['url']  = Check::Name($this->Data['titulo']);
        $this->Data['data'] = Check::Data($this->Data['data']);
        $this->Data['tipo'] = Check::Name($this->Data['tipo']);
        $this->Data['thumb'] = $Cover;
        $this->Data['content'] = $Content;
        $this->Data['cat_pai'] = $this->getCatParent();
    }

    //Obtem o ID da categoria PAI
    private function getCatParent() {
        $rCat = new Read;
        $rCat->ExeRead("cat_posts", "WHERE id = :id", "id={$this->Data['categoria']}");
        if ($rCat->getResult()):
            return $rCat->getResult()[0]['id_pai'];
        else:
            return null;
        endif;
    }

    //Verifica o NAME post. Se existir adiciona um pós-fix -Count
    private function setName() {
        $Where = (isset($this->Post) ? "id != {$this->Post} AND" : '');
        $readName = new Read;
        $readName->ExeRead(self::Entity, "WHERE {$Where} titulo = :t", "t={$this->Data['titulo']}");
        if ($readName->getResult()):
            $this->Data['url'] = $this->Data['url'] . '-' . $readName->getRowCount();
        endif;
    }

    //Cadastra o post no banco!
    private function Create() {
        $cadastra = new Create;
        $cadastra->ExeCreate(self::Entity, $this->Data);
        if ($cadastra->getResult()):
            $this->Error = ["O post {$this->Data['titulo']} foi cadastrado com sucesso no sistema!", RM_ACCEPT];
            $this->Result = $cadastra->getResult();
        endif;
    }

    //Atualiza o post no banco!
    private function Update() {
        $Update = new Update;
        $Update->ExeUpdate(self::Entity, $this->Data, "WHERE id = :id", "id={$this->Post}");
        if ($Update->getResult()):
            $this->Error = ["O post <b>{$this->Data['titulo']}</b> foi atualizado com sucesso no sistema!", RM_ACCEPT];
            $this->Result = true;
        endif;
    }

}
