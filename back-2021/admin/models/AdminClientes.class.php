<?php
/**
 * AdminClientes.class [ MODEL ADMIN ]
 * Respnsável por gerenciar os clientes no Admin do sistema!
 * 
 * @copyright (c) 2020, Renato Montanari - Informática Livre
 */
class AdminClientes {
    private $Data;
    private $Post;
    private $Error;
    private $Result;

    //Nome da tabela no banco de dados
    const Entity = 'usuario';

    /**
     * <b>Cadastrar Cliente:</b> Envelope os dados de um cliente em um array atribuitivo e execute esse método
     * para cadastrar o mesmo no sistema. Validações serão feitas!
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeCreate(array $Data) {
        $this->Data = $Data;
        $this->checkData();
        if($this->Result):
            
            $this->setData();     
        
            if($this->Data['avatar']): 
                $ImageName  = Check::Name($this->Data['nome']);               
                $ImgName    = "{$ImageName}-av-" . (substr(md5(time() + $i), 0, 5));
                $upload     = new Upload;
                $upload->ImageSemData($this->Data['avatar'], $ImgName, '200', 'avatars');
            endif;

            if (isset($upload) && $upload->getResult()):
                $this->Data['avatar'] = $upload->getResult();
                $this->Create();
            else:
                $this->Data['avatar'] = null;
                $_SESSION['errCapa'] = "<strong>Erro ao enviar a Foto:</strong> Tipo de arquivo inválido, envie imagens JPG ou PNG!";
                $this->Create();
            endif;  
            //var_dump($this->Data);
        endif;        
    }

    /**
     * <b>Atualizar Cliente:</b> Envelope os dados em uma array atribuitivo e informe o id de um
     * cliente para atualiza-lo no sistema!
     * @param INT $UserId = Id do cliente
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeUpdate($UserId, array $Data) {
        $this->Post = (int) $UserId;
        $this->Data = $Data;
        $this->checkData();
        if ($this->Result):
            $this->setData(); 
            $this->Data['uppdate'] = date('Y-m-d H:i:s');
            if (is_array($this->Data['avatar'])):
                $readCapa = new Read;
                $readCapa->ExeRead(self::Entity, "WHERE id = :post", "post={$this->Post}");
                $capa = '../uploads/' . $readCapa->getResult()[0]['avatar'];
                if (file_exists($capa) && !is_dir($capa)):
                    unlink($capa);
                endif;                
                $ImageName  = Check::Name($this->Data['nome']);                
                $uploadCapa = new Upload;
                $uploadCapa->ImageSemData($this->Data['avatar'], $ImageName, '200', 'avatars');               
            endif;
            
            if (isset($uploadCapa) && $uploadCapa->getResult()):
                $this->Data['avatar'] = $uploadCapa->getResult();
                $this->Update();
            else:
                unset($this->Data['avatar']);
                if(!empty($uploadCapa) && $uploadCapa->getError()):
                    RMErro("<strong>Erro ao enviar a Capa</strong>: " .$uploadCapa->getError(), E_USER_WARNING);
                endif;
                $this->Update();
            endif;
        endif;
    }
    
    

    /**
     * <b>Remover Cliente:</b> Informe o ID do cliente que deseja remover.     
     * @param INT $PostId = Id do cliente
     */
    public function ExeDelete($PostId) {
        $this->Post = (int) $PostId;
        $readCliente = new Read;
        $readCliente->ExeRead(self::Entity, "WHERE id = :id", "id={$this->Post}");

        if (!$readCliente->getResult()):
            $this->Error = ['Oppsss, você tentou remover um cliente que não existe no sistema!', RM_ERROR];
            $this->Result = false;
        else:       
            $PostDeletePost = $readCliente->getResult()[0];
            if (file_exists('../uploads/' . $PostDeletePost['avatar']) && !is_dir('../uploads/' . $PostDeletePost['avatar'])):
                unlink('../uploads/' . $PostDeletePost['avatar']);
            endif;
            $this->Delete();            
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
     
     //Valida e cria os dados para realizar o cadastro
    private function setData() {
        $Avatar = $this->Data['avatar'];
        $descricao = $this->Data['descricao'];
        unset($this->Data['avatar'], $this->Data['descricao']);
        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);        
        $this->Data['nasc']  = Check::Data($this->Data['nasc']);
        $this->Data['data']  = Check::Data($this->Data['data']);
        //$this->Data['token'] = md5($this->Data['email']);
        //$this->Data['tokendata'] = date('Y-m-d H:i:s');
        $this->Data['avatar'] = $Avatar;
        $this->Data['descricao'] = $descricao;   
        $this->Data['cat_pai'] = $this->getCatParent();
    }
    
    //Obtem o ID da categoria PAI
    private function getCatParent() {
        $rCat = new Read;
        $rCat->ExeRead("cat_clientes", "WHERE id = :id", "id={$this->Data['categoria']}");
        if ($rCat->getResult()):
            return $rCat->getResult()[0]['id_pai'];
        else:
            return null;
        endif;
    }

    //Verifica os dados digitados no formulário
    private function checkData() {
        if ($this->Data['nome'] == ''):
            $this->Error = ["Por favor, preencha o campo <strong>Nome</strong>!", RM_ERROR];
            $this->Result = false;
        elseif(!Check::Email($this->Data['email'])):
            $this->Error = ["O <b>email</b> informado não parece ter um formato válido!", RM_ERROR];
            $this->Result = false;
        elseif($this->Data['categoria'] == ''):
            $this->Error = ["Selecione uma <b>Categoria</b>!", RM_ERROR];
            $this->Result = false;
        else:
            $this->checkEmail();
        endif;
    }

    //Verifica cliente pelo e-mail, Impede cadastro duplicado!
    private function checkEmail() {
        $Where = ( isset($this->Post) ? "id != {$this->Post} AND" : '');
        $readUser = new Read;
        $readUser->ExeRead(self::Entity, "WHERE {$Where} email = :email", "email={$this->Data['email']}");
        if ($readUser->getRowCount()):
            $this->Error = ["O <b>email</b> informado foi cadastrado no sistema por outro cliente! Informe outro e-mail!", RM_ERROR];
            $this->Result = false;
        else:
            $this->Result = true;
        endif;
    }

    //Cadastra Cliente!
    private function Create() {
        $Create = new Create; 
        $Create->ExeCreate(self::Entity, $this->Data);
        if ($Create->getResult()):
            $this->Error = ["O cliente <b>{$this->Data['nome']}</b> foi cadastrado com sucesso no sistema!", RM_ACCEPT];
            $this->Result = $Create->getResult();
        endif;        
    }

    //Atualiza Cliente!
    private function Update() {
        $Update = new Update;  
        $Update->ExeUpdate(self::Entity, $this->Data, "WHERE id = :id", "id={$this->Post}");
        if ($Update->getResult()):
            $this->Error = ["O cliente <b>{$this->Data['nome']}</b> foi atualizado com sucesso!", RM_ACCEPT];
            $this->Result = true;
        endif;
    }

    //Remove Cliente
    private function Delete() {
        $Delete = new Delete;
        $Delete->ExeDelete(self::Entity, "WHERE id = :id", "id={$this->Post}");
        if ($Delete->getResult()):
            $this->Error = ["O Cliente <b>{$this->Data['nome']}</b> foi excluído com sucesso no sistema!", RM_ACCEPT];
            $this->Result = true;
        endif;
    }

}
