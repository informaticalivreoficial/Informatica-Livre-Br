<?php
/**
 * AdminUser.class [ MODEL ADMIN ]
 * Respnsável por gerenciar os usuários no Admin do sistema!
 * 
 * @copyright (c) 2014, Robson V. Leite UPINSIDE TECNOLOGIA
 */
class AdminUser {

    private $Data;
    private $User;
    private $Error;
    private $Result;

    //Nome da tabela no banco de dados
    const Entity = 'usuario';

    /**
     * <b>Cadastrar Usuário:</b> Envelope os dados de um usuário em um array atribuitivo e execute esse método
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
                $upload->Image($this->Data['avatar'], $ImgName, '200', 'avatars');
            endif;

            if (isset($upload) && $upload->getResult()):
                $this->Data['avatar'] = $upload->getResult();
                $this->Create();
            else:
                $this->Data['avatar'] = null;
                $_SESSION['errCapa'] = "<strong>Erro ao enviar a Foto:</strong> Tipo de arquivo inválido, envie imagens JPG ou PNG!";
                $this->Create();
            endif;
            
        endif;        
    }

    /**
     * <b>Atualizar Usuário:</b> Envelope os dados em uma array atribuitivo e informe o id de um
     * usuário para atualiza-lo no sistema!
     * @param INT $UserId = Id do usuário
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeUpdate($UserId, array $Data) {
        $this->User = (int) $UserId;
        $this->Data = $Data;
        //if (!$this->Data['senha']):
        //    unset($this->Data['senha']);
        //endif;
        $this->checkDataUpdate();

        if ($this->Result):
            if (is_array($this->Data['avatar'])):
                $readCapa = new Read;
                $readCapa->ExeRead(self::Entity, "WHERE id = :post", "post={$this->User}");
                $capa = '../uploads/' . $readCapa->getResult()[0]['avatar'];
                if (file_exists($capa) && !is_dir($capa)):
                    unlink($capa);
                endif;
                
                $ImageName  = Check::Name($this->Data['nome']);
                
                $uploadCapa = new Upload;
                $uploadCapa->Image($this->Data['avatar'], $ImageName, '200', 'avatars');               
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
            //$this->Update();
        endif;
    }
    
    

    /**
     * <b>Remover Usuário:</b> Informe o ID do usuário que deseja remover. Este método não permite deletar
     * o próprio perfil ou ainda remover todos os ADMIN'S do sistema!
     * @param INT $UserId = Id do usuário
     */
    public function ExeDelete($UserId) {
        $this->User = (int) $UserId;

        $readUser = new Read;
        $readUser->ExeRead(self::Entity, "WHERE id = :id", "id={$this->User}");

        if (!$readUser->getResult()):
            $this->Error = ['Oppsss, você tentou remover um usuário que não existe no sistema!', RM_ERROR];
            $this->Result = false;
        elseif ($this->User == $_SESSION['userlogin']['id']):
            $this->Error = ['Oppsss, você tentou remover seu usuário. Essa ação não é permitida!!!', RM_ERROR];
            $this->Result = false;
        else:       
            if ($readUser->getResult()[0]['nivel'] == 1):

                $readAdmin = $readUser;
                $readAdmin->ExeRead(self::Entity, "WHERE id != :id AND nivel = :lv", "id={$this->User}&lv=1");

                if (!$readAdmin->getRowCount()):
                    $this->Error = ['Oppsss, você está tentando remover o único ADMIN do sistema. Para remover cadastre outro antes!!!', RM_ERROR];
                    $this->Result = false;
                else:
                    $PostDeleteUser = $readUser->getResult()[0];
                    if (file_exists('../uploads/' . $PostDeleteUser['avatar']) && !is_dir('../uploads/' . $PostDeleteUser['avatar'])):
                        unlink('../uploads/' . $PostDeleteUser['avatar']);
                    endif;
                    $this->Delete();
                endif;

            else:
                $PostDeleteUser = $readUser->getResult()[0];
                if (file_exists('../uploads/' . $PostDeleteUser['avatar']) && !is_dir('../uploads/' . $PostDeleteUser['avatar'])):
                    unlink('../uploads/' . $PostDeleteUser['avatar']);
                endif;
                $this->Delete();
            endif;

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
        $this->Data['data']  = date('Y-m-d H:i:s');
        $this->Data['senha'] = md5($this->Data['code']);
        $this->Data['avatar'] = $Avatar;
        $this->Data['descricao'] = $descricao;        
    }

    //Verifica os dados digitados no formulário
    private function checkData() {
        if ($this->Data['nome'] == ''):
            $this->Error = ["Por favor, preencha o campo <strong>Nome</strong>!", RM_ERROR];
            $this->Result = false;
        elseif (!Check::Email($this->Data['email'])):
            $this->Error = ["O e-email informado não parece ter um formato válido!", RM_ERROR];
            $this->Result = false;
        elseif (isset($this->Data['code']) && (strlen($this->Data['code']) < 6 || strlen($this->Data['code']) > 12)):
            $this->Error = ["Sua senha deve conter entre 6 e 12 caracteres!", RM_ERROR];
            $this->Result = false;
        elseif ($this->Data['code'] != $this->Data['code1']):
            $this->Error = ["Senhas digitadas não conferem!", RM_ERROR];
            $this->Result = false;
        else:
            $this->checkEmail();
        endif;
    }
    
    //Verifica os dados digitados no formulário do update
    private function checkDataUpdate() {
        
        $this->Data['nasc']  = Check::Data($this->Data['nasc']);
        
        if ($this->Data['nome'] == ''):
            $this->Error = ["Por favor, preencha o campo <strong>Nome</strong>!", RM_ERROR];
            $this->Result = false;
        elseif (!Check::Email($this->Data['email'])):
            $this->Error = ["O e-email informado não parece ter um formato válido!", RM_ERROR];
            $this->Result = false;
        else:
            $this->checkEmail();
        endif;
    }

    //Verifica usuário pelo e-mail, Impede cadastro duplicado!
    private function checkEmail() {
        $Where = ( isset($this->User) ? "id != {$this->User} AND" : '');

        $readUser = new Read;
        $readUser->ExeRead(self::Entity, "WHERE {$Where} email = :email", "email={$this->Data['email']}");

        if ($readUser->getRowCount()):
            $this->Error = ["O e-email informado foi cadastrado no sistema por outro usuário! Informe outro e-mail!", RM_ERROR];
            $this->Result = false;
        else:
            $this->Result = true;
        endif;
    }

    //Cadasrtra Usuário!
    private function Create() {
        $Create = new Create;        

        $Create->ExeCreate(self::Entity, $this->Data);

        if ($Create->getResult()):
            $this->Error = ["O usuário <b>{$this->Data['nome']}</b> foi cadastrado com sucesso no sistema!", RM_ACCEPT];
            $this->Result = $Create->getResult();
        endif;        
    }

    //Atualiza Usuário!
    private function Update() {
        $Update = new Update;      

        $Update->ExeUpdate(self::Entity, $this->Data, "WHERE id = :id", "id={$this->User}");
        if ($Update->getResult()):
            $this->Error = ["O usuário <b>{$this->Data['nome']}</b> foi atualizado com sucesso!", RM_ACCEPT];
            $this->Result = true;
        endif;
    }

    //Remove Usuário
    private function Delete() {
        $Delete = new Delete;
        $Delete->ExeDelete(self::Entity, "WHERE id = :id", "id={$this->User}");
        if ($Delete->getResult()):
            $this->Error = ["Usuário removido com sucesso do sistema!", RM_ACCEPT];
            $this->Result = true;
        endif;
    }

}
