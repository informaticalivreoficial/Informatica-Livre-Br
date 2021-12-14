<?php

/**
 * AdminNewsletter.class [ MODEL ADMIN ]
 * Responsável por gerenciar os e-mails cadastrados no Admin do sistema!
 * 
 * @copyright (c) 2020, Renato Montanari/Informática Livre
 */
class AdminNewsletter {

    private $Data;
    private $Post;
    private $Error;
    private $Result;

    //Nome da tabela no banco de dados
    const Entity = 'newsletter';

    /**
     * <b>Cadastrar o Email:</b> Envelope os dados do email em um array atribuitivo e execute esse método
     * para cadastrar o email. Envia a capa automaticamente!
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeCreate(array $Data) {
        $this->Data = $Data;
        $this->checkData();
        if ($this->Result):        
            $this->setData();
            $this->Create();
        endif;
    }
    
    /**
     * <b>Cadastrar a Lista:</b> Envelope os dados da lista em um array atribuitivo e execute esse método
     * para cadastrar a lista. Envia a capa automaticamente!
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeCreateLista(array $Data) {
        $this->Data = $Data;
        $this->checkDataLista();
        if ($this->Result):        
            $this->setDataLista();
            $this->CreateLista();
        endif;
    }

    /**
     * <b>Atualizar Email:</b> Envelope os dados em uma array atribuitivo e informe o id de um 
     * email para atualizá-lo na tabela!
     * @param INT $PostId = Id do email
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeUpdate($PostId, array $Data) {
        $this->Post = (int) $PostId;
        $this->Data = $Data;
        $this->checkData();
        if ($this->Result):        
            $this->setData();
            $this->Data['uppdate'] = date('Y-m-d H:i:s');
            $this->Update(); 
        endif;
    }
    
    /**
     * <b>Atualizar Lista:</b> Envelope os dados em uma array atribuitivo e informe o id de uma 
     * lista para atualiza-la na tabela!
     * @param INT $PostId = Id da lista
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeUpdateLista($PostId, array $Data) {
        $this->Post = (int) $PostId;
        $this->Data = $Data;
        $this->checkDataLista();
        if ($this->Result):        
            $this->setDataLista();
            $this->Data['uppdate'] = date('Y-m-d H:i:s');
            $this->UpdateLista(); 
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
            $this->Error = ["O E-mail que você tentou deletar não existe no sistema!", RM_ERROR];
            $this->Result = false;
        else:
            $PostDelete = $ReadPost->getResult()[0];
            $deleta = new Delete;            
            $deleta->ExeDelete(self::Entity, "WHERE id = :postid", "postid={$this->Post}");
            $this->Error = ["O E-mail <b>{$PostDelete['email']}</b> foi removido com sucesso do sistema!", RM_ACCEPT];
            $this->Result = true;
        endif;
    }
    
    /**
     * <b>Deleta Lista:</b> Informe o ID da lista a ser removida para que esse método realize uma checagem de
     * emails excluindo todos os dados nessesários!
     * @param INT $PostId = Id da lista
     */
    public function ExeDeleteLista($PostId) {
        $this->Post = (int) $PostId;
        $ReadPost = new Read;
        $ReadPost->ExeRead("newsletter_cat", "WHERE id = :post", "post={$this->Post}");

        if (!$ReadPost->getResult()):
            $this->Error = ["A lista que você tentou deletar não existe no sistema!", RM_ERROR];
            $this->Result = false;
        else:
            $PostDelete = $ReadPost->getResult()[0];
            $deleta = new Delete;            
            $deleta->ExeDelete("newsletter_cat", "WHERE id = :postid", "postid={$this->Post}");
            $deletaEmail = new Delete;
            $deletaEmail->ExeDelete(self::Entity, "WHERE cat_id = :postid", "postid={$this->Post}");
            $this->Error = ["A Lista <b>{$PostDelete['titulo']}</b> foi removida com sucesso do sistema!", RM_ACCEPT];
            $this->Result = true;
        endif;
    }
    
    /**
     * <b>Ativa/Inativa o Email:</b> Informe o ID do email e o status e um status sendo 1 para ativo e 0 para
     * rascunho. Esse méto ativa e inativa os emails!
     * @param INT $PostId = Id do email
     * @param STRING $PostStatus = 1 para ativo, 0 para inativo
     */
    public function ExeStatus($PostId, $PostStatus) {
        $this->Post = (int) $PostId;
        $this->Data['status'] = (string) $PostStatus;
        $Update = new Update;
        $Update->ExeUpdate(self::Entity, $this->Data, "WHERE id = :id", "id={$this->Post}");
    }
    
    /**
     * <b>Ativa/Inativa Lista de E-mails:</b> Informe o ID da lista e o status e um status sendo 1 para ativo e 0 para
     * rascunho. Esse metodo ativa e inativa as listas!
     * @param INT $PostId = Id da lista
     * @param STRING $PostStatus = 1 para ativo, 0 para inativo
     */
    public function ExeStatusLista($PostId, $PostStatus) {
        $this->Post = (int) $PostId;
        $this->Data['status'] = (string) $PostStatus;
        $Update = new Update;
        $Update->ExeUpdate("newsletter_cat", $this->Data, "WHERE id = :id", "id={$this->Post}");
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
        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);
        $this->Data['data'] = Check::Data($this->Data['data']);
    } 
    
    //Valida e cria os dados para realizar o cadastro
    private function setDataLista() {
        $Content = $this->Data['content'];
        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);
        $this->Data['content'] = $Content;
        $this->Data['data'] = Check::Data($this->Data['data']);
    }
    
    //Verifica os dados digitados no formulário
    private function checkData() {
        if ($this->Data['nome'] == ''):
            $this->Error = ["Erro ao cadastrar: Por favor preencha o campo <strong>Nome</strong>!", RM_ERROR];
            $this->Result = false;
        elseif (!Check::Email($this->Data['email'])):
            $this->Error = ["O e-email informado não parece ter um formato válido!", RM_ALERT];
            $this->Result = false;
        elseif ($this->Data['cat_id'] == '0'):
            $this->Error = ["Erro ao cadastrar: Por favor selecione uma <b>Lista</b>", RM_INFOR];
            $this->Result = false;
        else:
            $this->checkEmail();
        endif;
    } 
    
    //Verifica os dados digitados no formulário
    private function checkDataLista() {
        if ($this->Data['titulo'] == ''):
            $this->Error = ["Erro ao cadastrar: Por favor preencha o campo <strong>Título da lista</strong>!", RM_ERROR];
            $this->Result = false;
        else:
            $this->Result = true;
        endif;
    } 
    
    //Verifica cadastro pelo e-mail, Impede cadastro duplicado!
    private function checkEmail() {
        $Where = ( isset($this->Post) ? "id != {$this->Post} AND" : '');
        $readUser = new Read;
        $readUser->ExeRead(self::Entity, "WHERE {$Where} email = :email", "email={$this->Data['email']}");
        if ($readUser->getRowCount()):
            $this->Error = ["O e-email informado já está cadastrado no sistema! Informe outro e-mail!", RM_ERROR];
            $this->Result = false;
        else:
            $this->Result = true;
        endif;
    }
    
    //Cadastra o email no banco!
    private function Create() {
        $cadastra = new Create;
        $cadastra->ExeCreate(self::Entity, $this->Data);
        if ($cadastra->getResult()):
            $this->Error = ["O E-mail {$this->Data['email']} foi cadastrado com sucesso no sistema!", RM_ACCEPT];
            $this->Result = $cadastra->getResult();
        endif;
    }
    
    //Cadastra a lista no banco!
    private function CreateLista() {
        $cadastra = new Create;
        $cadastra->ExeCreate("newsletter_cat", $this->Data);
        if ($cadastra->getResult()):
            $this->Error = ["A Lista {$this->Data['titulo']} foi cadastrada com sucesso no sistema!", RM_ACCEPT];
            $this->Result = $cadastra->getResult();
        endif;
    }
    
    //Atualiza o email no banco!
    private function Update() {
        $Update = new Update;
        $Update->ExeUpdate(self::Entity, $this->Data, "WHERE id = :id", "id={$this->Post}");
        if ($Update->getResult()):
            $this->Error = ["O E-mail <b>{$this->Data['email']}</b> foi atualizado com sucesso no sistema!", RM_ACCEPT];
            $this->Result = true;
        endif;
    }
    
    //Atualiza a lista no banco!
    private function UpdateLista() {
        $Update = new Update;
        $Update->ExeUpdate("newsletter_cat", $this->Data, "WHERE id = :id", "id={$this->Post}");
        if ($Update->getResult()):
            $this->Error = ["A Lista <b>{$this->Data['titulo']}</b> foi atualizada com sucesso no sistema!", RM_ACCEPT];
            $this->Result = true;
        endif;
    }

}