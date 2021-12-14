<?php
	
/**
 * AdminMenu.class [ MODEL ADMIN ]
 * Responável por gerenciar os Links do sistema no admin!
 * 
 * @copyright (c) 2005, Renato Montanari - Informática Livre
 */
class AdminMenu {
    
    private $Data;
    private $MenuId;
    private $Error;
    private $Result;

    //Nome da tabela no banco de dados!
    const Entity = 'menu_topo';
    
    
    /**
     * <b>Cadastrar Link:</b> Envelope titulo, descrição, data e id_pai em um array atribuitivo e execute esse método
     * para cadastrar o Link. Caso seja um sub-link, envie o id_pai como STRING null.
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeCreate(array $Data) {
        $this->Data = $Data;

        if($this->Data['nome'] == ''):            
            $this->Error = ['<b>Erro ao cadastrar:</b> Para cadastrar um Link, preencha ao menos o campo <strong>Título</strong>!', RM_ERROR];
            $this->Result = false;
        else:
            $this->setData();
            $this->setName();
            $this->Create();
        endif;
    }
    
    /**
     * <b>Atualizar Link:</b> Envelope os dados em uma array atribuitivo e informe o id de um
     * Link para atualiza-lo!
     * @param INT $LinkId = Id do Link
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeUpdate($LinkId, array $Data) {
        $this->MenuId = (int) $LinkId;
        $this->Data = $Data;

        if ($this->Data['nome'] == ''):            
            $this->Error = ["<b>Erro ao atualizar:</b> Para atualizar o Link {$this->Data['nome']}, preencha pelo menos o campo <strong>Título</strong>!", RM_ERROR];
            $this->Result = false;
        else:
            $this->setData();
            $this->setName();
            $this->Update();
        endif;
    }
    
    /**
     * <b>Deleta o Link:</b> Informe o ID de um Link para removê-lo do sistema. Esse método verifica
     * o tipo de Link e se é permitido excluir de acordo com os registros do sistema!
     * @param INT $LinkId = Id do Link
     */
    public function ExeDelete($LinkId) {
        $this->MenuId = (int) $LinkId;

        $read = new Read;
        $read->ExeRead(self::Entity, "WHERE id = :delid", "delid={$this->MenuId}");

        if (!$read->getResult()):
            $this->Result = false;
            $this->Error = ['Oppsss, você tentou remover um Link que não existe no sistema!', RM_INFOR];
        else:
            extract($read->getResult()[0]);
            if (!$id_pai && !$this->checkCats()):
                $this->Result = false;
                $this->Error = ["O Link <b>{$nome}</b> possui sub-Links cadastrados. Para deletar, antes altere ou remova os Sub-Links!", RM_ALERT];
            else:
                $delete = new Delete;
                $delete->ExeDelete(self::Entity, "WHERE id = :deletaid", "deletaid={$this->MenuId}");

                $tipo = ( empty($id_pai) ? 'Link' : 'Sub-Link' );
                $this->Result = true;
                $this->Error = ["O {$tipo} <b>{$nome}</b> foi removido com sucesso do sistema!", RM_ACCEPT];
            endif;
        endif;
    }
    
    /**
     * <b>Ativa/Inativa:</b> Informe o ID do link e o status e um status sendo 1 para ativo e 0 para
     * rascunho. Esse méto ativa e inativa os links!
     * @param INT $LinkId = Id do link
     * @param STRING $LinkId = 1 para ativo, 0 para inativo
     */
    public function ExeStatus($LinkId, $LinkStatus) {
        $this->MenuId = (int) $LinkId;
        $this->Data['status'] = (string) $LinkStatus;
        $Update = new Update;
        $Update->ExeUpdate(self::Entity, $this->Data, "WHERE id = :id", "id={$this->MenuId}");
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
        if($this->Data['link'] == '0'): $this->Data['link'] = ''; else: $this->Data['link'] = 'sessao/'.$this->Data['link']; endif;
        $this->Data['data'] = Check::Data($this->Data['data']);        
        $this->Data['uppdate'] = Check::Data($this->Data['uppdate']);
        $this->Data['id_pai'] = ($this->Data['id_pai'] == 'null' ? null : $this->Data['id_pai']);
    }

    //Verifica o NOME do Link. Se existir adiciona um pós-fix +1
    private function setName() {
        $Where = (!empty($this->MenuId) ? "id != {$this->MenuId} AND id_pai IS NULL AND" : '' );

        $readName = new Read;
        $readName->ExeRead(self::Entity, "WHERE {$Where} nome = :t", "t={$this->Data['nome']}");
        if ($readName->getResult()):
            $this->Data['nome'] = $this->Data['nome'] . '-' . $readName->getRowCount();
        endif;
    }
    
    //Verifica Links da seção
    private function checkCats() {
        $readSes = new Read;
        $readSes->ExeRead(self::Entity, "WHERE id_pai = :parent", "parent={$this->MenuId}");
        if ($readSes->getResult()):
            return false;
        else:
            return true;
        endif;
    }
    
    //Cadastra o Link no banco!
    private function Create() {
        $Create = new Create;
        $Create->ExeCreate(self::Entity, $this->Data);
        if ($Create->getResult()):
            $this->Result = $Create->getResult();
            $this->Error = ["<b>Sucesso:</b> O Link {$this->Data['nome']} foi cadastrado no sistema!", RM_ACCEPT];
        endif;
    }
    
    //Atualiza o Link
    private function Update() {
        $Update = new Update;
        $Update->ExeUpdate(self::Entity, $this->Data, "WHERE id = :linkid", "linkid={$this->MenuId}");
        if ($Update->getResult()):
            $tipo = ( empty($this->Data['id_pai'] != null) ? 'Link' : 'Sub-Link' );
            $this->Result = true;
            $this->Error = ["<b>Sucesso:</b> O {$tipo} {$this->Data['nome']} foi atualizado no sistema!", RM_ACCEPT];
        endif;
    }
    
}