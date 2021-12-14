<?php

/**
 * AdminComentarios.class [ MODEL ADMIN ]
 * Respnsável por gerenciar os comentários no Admin do sistema!
 * 
 * @copyright (c) 2020, Renato Montanari INFORMÁTICA LIVRE
 */
class AdminComentarios {

    private $Data;
    private $Post;
    private $Error;
    private $Result;

    //Nome da tabela no banco de dados
    const Entity = 'comentarios';

    /**
     * <b>Atualizar Comentário:</b> Envelope os dados em uma array atribuitivo e informe o id de um 
     * post para atualiza-lo na tabela!
     * @param INT $PostId = Id do post
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeUpdate($PostId, array $Data) {
        $this->Post = (int) $PostId;
        $this->Data = $Data;

        if ($this->Data['nome'] == ''):
            $this->Error = ["Erro ao cadastrar: Por favor preencha o campo <strong>Nome</strong>!", RM_ERROR];
            $this->Result = false;
        else:
            //$this->setData();
            $this->Data['uppdate'] = date('Y-m-d H:i:s');
            $this->Update();
        endif;
    }

    /**
     * <b>Deleta Comentário:</b> Informe o ID do comentário a ser removido para que esse método realize uma checagem de
     * excluindo todos os dados nessesários!
     * @param INT $PostId = Id do post
     */
    public function ExeDelete($PostId) {
        $this->Post = (int) $PostId;
        $ReadPost = new Read;
        $ReadPost->ExeRead(self::Entity, "WHERE id = :post", "post={$this->Post}");
        if (!$ReadPost->getResult()):
            $this->Error = ["O comentário que você tentou deletar não existe no sistema!", RM_ERROR];
            $this->Result = false;
        else:
            $PostDelete = $ReadPost->getResult()[0];
            $deleta = new Delete;
            $deleta->ExeDelete(self::Entity, "WHERE id = :postid", "postid={$this->Post}");
            $this->Error = ["O comentário de <b>{$PostDelete['nome']}</b> foi removido com sucesso do sistema!", RM_ACCEPT];
            $this->Result = true;
        endif;
    }

    /**
     * <b>Ativa/Inativa Comentário:</b> Informe o ID do comentário e o status sendo 1 para Aprovado e 0 para
     * Pendente. Esse méto ativa e inativa os comentários!
     * @param INT $PostId = Id do comentário
     * @param STRING $PostStatus = 1 para ativo, 0 para inativo
     */
    public function ExeStatus($PostId, $PostStatus) {
        $this->Post = (int) $PostId;
        $this->Data['status'] = (string) $PostStatus;
        $Update = new Update;
        $Update->ExeUpdate(self::Entity, $this->Data, "WHERE id = :id", "id={$this->Post}");
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

    //Atualiza o post no banco!
    private function Update() {
        $Update = new Update;
        $Update->ExeUpdate(self::Entity, $this->Data, "WHERE id = :id", "id={$this->Post}");
        if ($Update->getResult()):
            $this->Error = ["O Comentário de <b>{$this->Data['nome']}</b> foi atualizado com sucesso no sistema!", RM_ACCEPT];
            $this->Result = true;
        endif;
    }

}

