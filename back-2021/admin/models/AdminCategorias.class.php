<?php

/**
 * AdminCategory.class [ MODEL ADMIN ]
 * Responável por gerenciar as categorias do sistema no admin!
 * 
 * @copyright (c) 2014, Robson V. Leite UPINSIDE TECNOLOGIA
 */
class AdminCategorias {

    private $Data;
    private $CatId;
    private $Tabela;
    private $TabelaPosts;
    private $Error;
    private $Result;

    //Nome da tabela no banco de dados!
    //const Entity = 'categorias';

    /**
     * <b>Cadastrar Categoria:</b> Envelope titulo, descrição, data e sessão em um array atribuitivo e execute esse método
     * para cadastrar a categoria. Case seja uma sessão, envie o category_parent como STRING null.
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeCreate($Tabela, array $Data) {
        $this->Data   = $Data;
        $this->Tabela = $Tabela;

        if($this->Data['nome'] == ''):            
            $this->Error = ['<b>Erro ao cadastrar:</b> Para cadastrar uma categoria, preencha ao menos o campo <strong>Nome da Categoria</strong>!', RM_ERROR];
            $this->Result = false;
        //elseif():
        
        else:
            $this->setData();
            $this->setName();
            $this->Create();
        endif;
    }

    /**
     * <b>Atualizar Categoria:</b> Envelope os dados em uma array atribuitivo e informe o id de uma
     * categoria para atualiza-la!
     * @param INT $CategoryId = Id da categoria
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeUpdate($Tabela, $CategoryId, array $Data) {
        $this->CatId = (int) $CategoryId;
        $this->Data = $Data;
        $this->Tabela = $Tabela;

        if ($this->Data['nome'] == ''):            
            $this->Error = ["<b>Erro ao atualizar:</b> Para atualizar a categoria {$this->Data['nome']}, preencha pelo menos o campo <strong>Nome da Categoria</strong>!", RM_ERROR];
            $this->Result = false;
        else:
            $this->setData();
            $this->setName();
            $this->Data['uppdate'] = date('Y-m-d H:i:s');
            $this->Update();
        endif;
    }

    /**
     * <b>Deleta categoria:</b> Informe o ID de uma categoria para remove-la do sistema. Esse método verifica
     * o tipo de categoria e se é permitido excluir de acordo com os registros do sistema!
     * @param INT $CategoryId = Id da categoria
     */
    public function ExeDelete($Tabela, $tabelaPosts, $CategoryId) {
        $this->CatId = (int) $CategoryId;
        $this->Tabela = $Tabela;
        $this->TabelaPosts = $tabelaPosts;
        
        $read = new Read;
        $read->ExeRead($this->Tabela, "WHERE id = :delid", "delid={$this->CatId}");

        if (!$read->getResult()):
            $this->Result = false;
            $this->Error = ['Oppsss, você tentou remover uma categoria que não existe no sistema!', RM_INFOR];
        else:
            extract($read->getResult()[0]);
            if (!$id_pai && !$this->checkCats()):
                $this->Result = false;
                $this->Error = ["A Categoria <b>{$nome}</b> possui sub-categorias cadastradas. Para deletar, antes altere ou remova as Sub-Categorias!", RM_ALERT];
            elseif ($id_pai && !$this->checkPosts($this->TabelaPosts)):
                $this->Result = false;
                $this->Error = ["A Categoria <b>{$nome}</b> possui dados cadastrados. Para deletar, antes altere ou remova todos os posts desta categoria!", RM_ALERT];
            else:
                $delete = new Delete;
                $delete->ExeDelete($this->Tabela, "WHERE id = :deletaid", "deletaid={$this->CatId}");

                $tipo = ( empty($id_pai) ? 'Categoria' : 'Sub-Categoria' );
                $this->Result = true;
                $this->Error = ["A {$tipo} <b>{$nome}</b> foi removida com sucesso do sistema!", RM_ACCEPT];
            endif;
        endif;
    }

    /**
     * <b>Verificar Cadastro:</b> Retorna TRUE se o cadastro ou update for efetuado ou FALSE se não. Para verificar
     * erros execute um getError();
     * @return BOOL $Var = True or False
     */
    public function getResult() {
        return $this->Result;
    }

    /**
     * <b>Obter Erro:</b> Retorna um array associativo com a mensagem e o tipo de erro!
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
        $this->Data['url'] = Check::Name($this->Data['nome']);
        $this->Data['data'] = Check::Data($this->Data['data']);
        $this->Data['id_pai'] = ($this->Data['id_pai'] == 'null' ? null : $this->Data['id_pai']);
    }

    //Verifica o NAME da categoria. Se existir adiciona um pós-fix +1
    private function setName() {
        $Where = (!empty($this->CatId) ? "id != {$this->CatId} AND" : '' );

        $readName = new Read;
        $readName->ExeRead($this->Tabela, "WHERE {$Where} nome = :t", "t={$this->Data['nome']}");
        if ($readName->getResult()):
            $this->Data['url'] = $this->Data['url'] . '-' . $readName->getRowCount();
        endif;
    }

    //Verifica categorias da seção
    private function checkCats() {
        $readSes = new Read;
        $readSes->ExeRead($this->Tabela, "WHERE id_pai = :parent", "parent={$this->CatId}");
        if ($readSes->getResult()):
            return false;
        else:
            return true;
        endif;
    }

    //Verifica artigos da categoria
    private function checkPosts($tabela) {
        $this->TabelaPosts = $tabela;
        $readPosts = new Read;
        $readPosts->ExeRead($this->TabelaPosts, "WHERE categoria = :category", "category={$this->CatId}");
        if ($readPosts->getResult()):
            return false;
        else:
            return true;
        endif;
    }

    //Cadastra a categoria no banco!
    private function Create() {
        $Create = new Create;
        $Create->ExeCreate($this->Tabela, $this->Data);
        if ($Create->getResult()):
            $this->Result = $Create->getResult();
            $this->Error = ["<b>Sucesso:</b> A categoria {$this->Data['nome']} foi cadastrada no sistema!", RM_ACCEPT];
        endif;
    }

    //Atualiza Categoria
    private function Update() {
        $Update = new Update;
        $Update->ExeUpdate($this->Tabela, $this->Data, "WHERE id = :catid", "catid={$this->CatId}");
        if ($Update->getResult()):
            $tipo = ( empty($this->Data['id_pai'] != null) ? 'Categoria' : 'Sub-Categoria' );
            $this->Result = true;
            $this->Error = ["<b>Sucesso:</b> A {$tipo} {$this->Data['nome']} foi atualizada no sistema!", RM_ACCEPT];
        endif;
    }

}
