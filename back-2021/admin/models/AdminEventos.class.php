<?php

/**
 * AdminEventos.class [ MODEL ADMIN ]
 * Responsável por gerenciar os eventos no Admin do sistema!
 *
 * @copyright (c) 2020, Renato Montanari / Informática Livre
 */
class AdminEventos {

    private $Data;
    private $Post;
    private $Error;
    private $Result;

    //Nome da tabela no banco de dados
    const Entity = 'eventos';

    /**
     * <b>Cadastrar o Evento:</b> Envelope os dados do post em um array atribuitivo e execute esse método
     * para cadastrar o evento. Envia a capa automaticamente!
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeCreate(array $Data) {
        $this->Data = $Data;

        if ($this->Data['titulo'] == ''):
            $this->Error = ["Erro ao cadastrar: Por favor preencha o campo <strong>Título</strong>!", RM_ERROR];
            $this->Result = false;
        elseif($this->Data['capacidade'] == ''):
            $this->Error = ["Erro ao cadastrar: Por favor insira o <strong>Número de Vagas</strong>!", RM_ERROR];
            $this->Result = false;
        else:
            $this->setData();
            $this->setName();

            if ($this->Data['thumb']):
                $pasta 	= 'eventos';

                $upload = new Upload;
                $upload->Image($this->Data['thumb'], $this->Data['url'], '1024', $pasta.'/capas');
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
     * <b>Cadastrar Reserva:</b> Envelope os dados da reserva em um array atribuitivo e execute esse método
     * para cadastrar.
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeCreateReserva(array $Data)
    {
        $this->Data = $Data;
        if ($this->Data['nome'] == ''):
            $this->Error = ["Erro ao cadastrar: Por favor preencha o campo <strong>Nome</strong>!", RM_ERROR];
            $this->Result = false;
        elseif(!Check::Email($this->Data['email'])):
            $this->Error = ["Erro ao cadastrar: O <strong>email</strong> informado não parece ter um formato válido!", RM_ERROR];
            $this->Result = false;
        else:
            $this->Data['data'] = date('Y-m-d H:i:s');
            $this->CreateReserva();
        endif;
    }

    /**
     * <b>Atualizar Reserva:</b> Envelope os dados em uma array atribuitivo e informe o id de uma
     * reserva para atualiza-la na tabela!
     * @param INT $PostId = Id da Reserva
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeUpdateReserva($PostId, array $Data)
    {
        $this->Post = (int) $PostId;
        $this->Data = $Data;
        if ($this->Data['nome'] == ''):
            $this->Error = ["Erro ao cadastrar: Por favor preencha o campo <strong>Nome</strong>!", RM_ERROR];
            $this->Result = false;
        elseif(!Check::Email($this->Data['email'])):
            $this->Error = ["Erro ao cadastrar: O <strong>email</strong> informado não parece ter um formato válido!", RM_ERROR];
            $this->Result = false;
        else:
            $this->Data['uppdate'] = date('Y-m-d H:i:s');
            $this->UpdateReserva();
        endif;
    }

    /**
     * <b>Atualizar Evento:</b> Envelope os dados em uma array atribuitivo e informe o id de um
     * evento para atualiza-lo na tabela!
     * @param INT $PostId = Id do post
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeUpdate($PostId, array $Data) {
        $this->Post = (int) $PostId;
        $this->Data = $Data;

        if ($this->Data['titulo'] == ''):
            $this->Error = ["Erro ao cadastrar: Por favor preencha o campo <strong>Título</strong>!", RM_ERROR];
            $this->Result = false;
        elseif($this->Data['capacidade'] == ''):
            $this->Error = ["Erro ao cadastrar: Por favor insira o <strong>Número de Vagas</strong>!", RM_ERROR];
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

                $pasta 	= 'eventos';

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
 * <b>Deleta Evento:</b> Informe o ID do evento a ser removido para que esse método realize uma checagem de
 * reservas e excluinto todos os dados nessesários!
 * @param INT $PostId = Id do post
 */
    public function ExeDelete($PostId) {
        $this->Post = (int) $PostId;

        $ReadPost = new Read;
        $ReadPost->ExeRead(self::Entity, "WHERE id = :post", "post={$this->Post}");

        if (!$ReadPost->getResult()):
            $this->Error = ["O evento que você tentou deletar não existe no sistema!", RM_ERROR];
            $this->Result = false;
        else:
            $PostDelete = $ReadPost->getResult()[0];

            $readReservas = new Read;
            $readReservas->ExeRead("eventos_reserva", "WHERE evento_id = :id", "id={$this->Post}");
            if ($readReservas->getResult()):
                $this->Error = ["Este evento possui reservas, tem certeza que deseja excluir? <a href='painel.php?exe=eventos/eventos&post={$this->Post}&action=deleteS'>[Sim]</a>", RM_ALERT];
                $this->Result = false;
            else:
                $deleta = new Delete;
                $deleta->ExeDelete(self::Entity, "WHERE id = :postid", "postid={$this->Post}");
                $this->Error = ["O evento <b>{$PostDelete['titulo']}</b> foi removido com sucesso do sistema!", RM_ACCEPT];
                $this->Result = true;
            endif;
        endif;
    }

    /**
     * <b>Deleta Reserva Confirmado:</b> Informe o ID da reserva a ser removida para que esse método realize
     * uma checagem e exclua os dados nessesários!
     * @param INT $PostId = Id da Reserva
     */
    public function deleteReserva($PostId)
    {
        $this->Post = (int) $PostId;
        $ReadPost = new Read;
        $ReadPost->ExeRead("eventos_reserva", "WHERE id = :post", "post={$this->Post}");
        if (!$ReadPost->getResult()):
            $this->Error = ["A reserva que você tentou deletar não existe no sistema!", RM_ERROR];
            $this->Result = false;
        else:
            $PostDelete = $ReadPost->getResult()[0];
            $deleta = new Delete;
            $deleta->ExeDelete("eventos_reserva", "WHERE id = :postid", "postid={$this->Post}");
            $this->Error = ["A reserva de <b>{$PostDelete['nome']}</b> foi removida com sucesso do sistema!", RM_ACCEPT];
        endif;
    }

    /**
     * <b>Deleta Evento Confirmado:</b> Informe o ID do evento a ser removido para que esse método realize uma checagem de
     * reservas e excluinto todos os dados nessesários!
     * @param INT $PostId = Id do post
     */
    public function ExeDeleteS($PostId) {
        $this->Post = (int) $PostId;

        $ReadPost = new Read;
        $ReadPost->ExeRead(self::Entity, "WHERE id = :post", "post={$this->Post}");

        if (!$ReadPost->getResult()):
            $this->Error = ["O evento que você tentou deletar não existe no sistema!", RM_ERROR];
            $this->Result = false;
        else:
            $PostDelete = $ReadPost->getResult()[0];

            $readReservas = new Read;
            $readReservas->ExeRead("eventos_reserva", "WHERE evento_id = :id", "id={$this->Post}");
            if ($readReservas->getResult()):
                $deleta = new Delete;
                $deleta->ExeDelete("eventos_reserva", "WHERE evento_id = :postid", "postid={$this->Post}");
                $deleta->ExeDelete(self::Entity, "WHERE id = :postid", "postid={$this->Post}");
                $this->Error = ["O evento <b>{$PostDelete['titulo']}</b> foi removido com sucesso do sistema!", RM_ACCEPT];
                $this->Result = true;
            endif;
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
        $Content = $this->Data['descricao'];
        unset($this->Data['thumb'], $this->Data['descricao']);

        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);

        $this->Data['url']  = Check::Name($this->Data['titulo']);
        $this->Data['data'] = Check::Data($this->Data['data']);
        $this->Data['datainicio'] = Check::Data($this->Data['datainicio']);
        $this->Data['datatermino'] = Check::Data($this->Data['datatermino']);
        $this->Data['thumb'] = $Cover;
        $this->Data['thumb'] = $Cover;
        $this->Data['descricao'] = $Content;
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

    //Cadastra o evento no banco!
    private function Create() {
        $cadastra = new Create;
        $cadastra->ExeCreate(self::Entity, $this->Data);
        if ($cadastra->getResult()):
            $this->Error = ["O evento {$this->Data['titulo']} foi cadastrado com sucesso no sistema!", RM_ACCEPT];
            $this->Result = $cadastra->getResult();
        endif;
    }

    //Cadastra a reserva no banco!
    private function CreateReserva() {
        $cadastra = new Create;
        $cadastra->ExeCreate("eventos_reserva", $this->Data);
        if ($cadastra->getResult()):
            $this->Error = ["A reserva de {$this->Data['nome']} foi cadastrada com sucesso no sistema!", RM_ACCEPT];
            $this->Result = $cadastra->getResult();
        endif;
    }

    //Atualiza o evento no banco!
    private function Update() {
        $Update = new Update;
        $Update->ExeUpdate(self::Entity, $this->Data, "WHERE id = :id", "id={$this->Post}");
        if ($Update->getResult()):
            $this->Error = ["O post <b>{$this->Data['titulo']}</b> foi atualizado com sucesso no sistema!", RM_ACCEPT];
            $this->Result = true;
        endif;
    }

    //Atualiza a reserva no banco!
    private function UpdateReserva() {
        $Update = new Update;
        $Update->ExeUpdate("eventos_reserva", $this->Data, "WHERE id = :id", "id={$this->Post}");
        if ($Update->getResult()):
            $this->Error = ["A reserva de <b>{$this->Data['nome']}</b> foi atualizada com sucesso no sistema!", RM_ACCEPT];
            $this->Result = true;
        endif;
    }

}
