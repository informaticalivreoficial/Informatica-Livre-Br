<?php

/**
 * AdminEmpresas.class [ MODEL ADMIN ]
 * Respnsável por gerenciar as empresas no Admin do sistema!
 *
 * @copyright (c) 2020, Renato Montanari - Informática Livre
 */
class AdminEmpresas {
    private $Data;
    private $Post;
    private $Error;
    private $Result;

    //Nome da tabela no banco de dados
    const Entity = 'empresas';

    /**
     * <b>Cadastrar a Empresa:</b> Envelope os dados da empresa em um array atribuitivo e execute esse método
     * para cadastrar a empresa. Envia a capa automaticamente!
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeCreate(array $Data) {
        $this->Data = $Data;
        $this->checkData();
        if ($this->Result):
            $this->setData();
            $this->setName();
            if ($this->Data['img']):
                $upload = new Upload;
                $upload->ImageSemData($this->Data['img'], $this->Data['url'], '300', 'empresas');
            endif;
            if (isset($upload) && $upload->getResult()):
                $this->Data['img'] = $upload->getResult();
                $this->Create();
            else:
                $this->Data['img'] = null;
                $_SESSION['errCapa'] = "<strong>Você não enviou uma Imagem</strong> ou o <strong>Tipo de arquivo é inválido</strong>, envie imagens JPG ou PNG!";
                $this->Create();
            endif;
        endif;
    }

    /**
     * <b>Atualizar Empresa:</b> Envelope os dados em uma array atribuitivo e informe o id de uma
     * empresa para atualiza-lo na tabela!
     * @param INT $PostId = Id da empresa
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeUpdate($PostId, array $Data) {
        $this->Post = (int) $PostId;
        $this->Data = $Data;
        $this->checkData();
        if ($this->Result):
            $this->setData();
            $this->setName();
            $this->Data['uppdate'] = date('Y-m-d H:i:s');

            if (is_array($this->Data['img'])):
                $readCapa = new Read;
                $readCapa->ExeRead(self::Entity, "WHERE id = :post", "post={$this->Post}");
                $capa = '../uploads/' . $readCapa->getResult()[0]['img'];
                if (file_exists($capa) && !is_dir($capa)):
                    unlink($capa);
                endif;
                $uploadCapa = new Upload;
                $uploadCapa->ImageSemData($this->Data['img'], $this->Data['url'], '300', 'empresas');
            endif;

            if (isset($uploadCapa) && $uploadCapa->getResult()):
                $this->Data['img'] = $uploadCapa->getResult();
                $this->Update();
            else:
                unset($this->Data['img']);
                if(!empty($uploadCapa) && $uploadCapa->getError()):
                    RMErro("<strong>Erro ao enviar a Imagem</strong>: " .$uploadCapa->getError(), E_USER_WARNING);
                endif;
                $this->Update();
            endif;
        endif;
    }

    /**
     * <b>Deleta Empresa:</b> Informe o ID da empresa a ser removida para que esse método realize uma checagem de
     * pastas e galerias excluindo todos os dados nessessários!
     * @param INT $PostId = Id da empresa
     */
    public function ExeDelete($PostId) {
        $this->Post = (int) $PostId;

        $ReadPost = new Read;
        $ReadPost->ExeRead(self::Entity, "WHERE id = :post", "post={$this->Post}");

        if (!$ReadPost->getResult()):
            $this->Error = ["A empresa que você tentou deletar não existe no sistema!", RM_ERROR];
            $this->Result = false;
        else:
            $PostDelete = $ReadPost->getResult()[0];
            if (file_exists('../uploads/' . $PostDelete['img']) && !is_dir('../uploads/' . $PostDelete['img'])):
                unlink('../uploads/' . $PostDelete['img']);
            endif;
            $deleta = new Delete;
            $deleta->ExeDelete(self::Entity, "WHERE id = :postid", "postid={$this->Post}");

            $this->Error = ["A empresa <b>{$PostDelete['nome']}</b> foi removida com sucesso do sistema!", RM_ACCEPT];
            $this->Result = true;
        endif;
    }

    /**
     * <b>Ativa/Inativa Empresa:</b> Informe o ID da empresa e o status sendo 1 para ativo e 0 para
     * rascunho. Esse método ativa e inativa as empresas!
     * @param INT $PostId = Id da empresa
     * @param STRING $PostStatus = 1 para ativo, 0 para inativo
     */
    public function ExeStatus($PostId, $PostStatus) {
        $this->Post = (int) $PostId;
        $this->Data['status'] = (string) $PostStatus;
        $Update = new Update;
        $Update->ExeUpdate(self::Entity, $this->Data, "WHERE id = :id", "id={$this->Post}");
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
        $Cover = $this->Data['img'];
        $Content = $this->Data['descricao'];
        unset($this->Data['img'], $this->Data['descricao']);
        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);
        $this->Data['url']  = Check::Name($this->Data['nome']);
        $this->Data['data'] = Check::Data($this->Data['data']);
        $this->Data['img'] = $Cover;
        $this->Data['descricao'] = $Content;
    }

    //Verifica os dados digitados no formulário
    private function checkData() {
        if ($this->Data['nome'] == ''):
            $this->Error = ["Erro ao cadastrar: Por favor preencha o campo <strong>Nome da Empresa</strong>!", RM_ERROR];
            $this->Result = false;
        elseif (!Check::Email($this->Data['email'])):
            $this->Error = ["O e-mail informado não parece ter um formato válido!", RM_ERROR];
            $this->Result = false;
        else:
            $this->checkEmail();
        endif;
    }

    //Verifica empresa pelo e-mail, Impede cadastro duplicado!
    private function checkEmail() {
        $Where = ( isset($this->Post) ? "id != {$this->Post} AND" : '');
        $readUser = new Read;
        $readUser->ExeRead(self::Entity, "WHERE {$Where} email = :email", "email={$this->Data['email']}");
        if ($readUser->getRowCount()):
            $this->Error = ["O e-email informado foi cadastrado no sistema por outra empresa! Informe outro e-mail!", RM_ERROR];
            $this->Result = false;
        else:
            $this->Result = true;
        endif;
    }

    //Verifica o NAME post. Se existir adiciona um pós-fix -Count
    private function setName() {
        $Where = (isset($this->Post) ? "id != {$this->Post} AND" : '');
        $readName = new Read;
        $readName->ExeRead(self::Entity, "WHERE {$Where} nome = :t", "t={$this->Data['nome']}");
        if ($readName->getResult()):
            $this->Data['url'] = $this->Data['url'] . '-' . $readName->getRowCount();
        endif;
    }

    //Cadastra o post no banco!
    private function Create() {
        $cadastra = new Create;
        $cadastra->ExeCreate(self::Entity, $this->Data);
        if ($cadastra->getResult()):
            $this->Error = ["A empresa <b>{$this->Data['nome']}</b> foi cadastrada com sucesso no sistema!", RM_ACCEPT];
            $this->Result = $cadastra->getResult();
        endif;
    }

    //Atualiza o post no banco!
    private function Update() {
        $Update = new Update;
        $Update->ExeUpdate(self::Entity, $this->Data, "WHERE id = :id", "id={$this->Post}");
        if ($Update->getResult()):
            $this->Error = ["A Empresa <b>{$this->Data['nome']}</b> foi atualizada com sucesso no sistema!", RM_ACCEPT];
            $this->Result = true;
        endif;
    }

}
