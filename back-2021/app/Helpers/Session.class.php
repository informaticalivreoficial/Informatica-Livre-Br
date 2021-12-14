<?php

/**
 * Session.class [ HELPER ]
 * Responsável pelas estatísticas, sessões e atualizações de tráfego do sistema!
 * 
 * @copyright (c) 2017, Renato Montanari
 */
class Session {

    private $Date;
    private $Cache;
    private $Traffic;
    private $Browser;

    function __construct($Cache = null) {
        session_start();
        $this->CheckSession($Cache);
    }

    //Verifica e executa todos os métodos da classe!
    private function CheckSession($Cache = null) {
        $this->Date = date('Y-m-d');
        $this->Cache = ( (int) $Cache ? $Cache : 20 );

        if (empty($_SESSION['useronline'])):
            $this->setTraffic();
            $this->setSession();
            $this->CheckBrowser();
            $this->setUsuario();
            $this->BrowserUpdate();
        else:
            $this->TrafficUpdate();
            $this->sessionUpdate();
            $this->CheckBrowser();
            $this->UsuarioUpdate();
        endif;

        $this->Date = null;
    }

    /*
     * ***************************************
     * ********   SESSÃO DO USUÁRIO   ********
     * ***************************************
     */

    //Inicia a sessão do usuário
    private function setSession() {
        $_SESSION['useronline'] = [
            "session" => session_id(),
            "startview" => date('Y-m-d H:i:s'),
            "endview" => date('Y-m-d H:i:s', strtotime("+{$this->Cache}minutes")),
            "ip" => filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP),
            "url" => filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_DEFAULT),
            "agent" => filter_input(INPUT_SERVER, "HTTP_USER_AGENT", FILTER_DEFAULT)
        ];
    }

    //Atualiza sessão do usuário!
    private function sessionUpdate() {
        $_SESSION['useronline']['endview'] = date('Y-m-d H:i:s', strtotime("+{$this->Cache}minutes"));
        $_SESSION['useronline']['url'] = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_DEFAULT);
    }

    /*
     * ***************************************
     * *** USUÁRIOS, VISITAS, ATUALIZAÇÕES ***
     * ***************************************
     */

    //Verifica e insere o tráfego na tabela
    private function setTraffic() {
        $this->getTraffic();
        if (!$this->Traffic):
            $ArrSiteViews = [ 'data' => $this->Date, 'usuarios' => 1, 'views' => 1, 'paginas' => 1];
            $createSiteViews = new Create;
            $createSiteViews->ExeCreate('siteviews', $ArrSiteViews);
        else:
            if (!$this->getCookie()):
                $ArrSiteViews = [ 'usuarios' => $this->Traffic['usuarios'] + 1, 'views' => $this->Traffic['views'] + 1, 'paginas' => $this->Traffic['paginas'] + 1];
            else:
                $ArrSiteViews = [ 'views' => $this->Traffic['views'] + 1, 'paginas' => $this->Traffic['paginas'] + 1];
            endif;

            $updateSiteViews = new Update;
            $updateSiteViews->ExeUpdate('siteviews', $ArrSiteViews, "WHERE data = :date", "date={$this->Date}");

        endif;
    }

    //Verifica e atualiza os pageviews
    private function TrafficUpdate() {
        $this->getTraffic();
        $ArrSiteViews = [ 'paginas' => $this->Traffic['paginas'] + 1];
        $updatePageViews = new Update;
        $updatePageViews->ExeUpdate('siteviews', $ArrSiteViews, "WHERE data = :date", "date={$this->Date}");

        $this->Traffic = null;
    }

    //Obtém dados da tabele [ HELPER TRAFFIC ]
    //siteviews
    private function getTraffic() {
        $readSiteViews = new Read;
        $readSiteViews->ExeRead('siteviews', "WHERE data = :date", "date={$this->Date}");
        if ($readSiteViews->getRowCount()):
            $this->Traffic = $readSiteViews->getResult()[0];
        endif;
    }

    //Verifica, cria e atualiza o cookie do usuário [ HELPER TRAFFIC ]
    private function getCookie() {
        $Cookie = filter_input(INPUT_COOKIE, 'useronline', FILTER_DEFAULT);
        setcookie("useronline", base64_encode("infolivre"), time() + 86400);
        if (!$Cookie):
            return false;
        else:
            return true;
        endif;
    }

    /*
     * ***************************************
     * *******  NAVEGADORES DE ACESSO   ******
     * ***************************************
     */

    //Identifica navegador do usuário!
    private function CheckBrowser() {
        $this->Browser = $_SESSION['useronline']['agent'];
        if (strpos($this->Browser, 'Chrome')):
            $this->Browser = 'Chrome';
        elseif (strpos($this->Browser, 'Firefox')):
            $this->Browser = 'Firefox';
        elseif (strpos($this->Browser, 'Edge')):
            $this->Browser = 'Edge';
        elseif (strpos($this->Browser, 'MSIE') || strpos($this->Browser, 'Trident/')):
            $this->Browser = 'IE';
        else:
            $this->Browser = 'Outros';
        endif;
    }

    //Atualiza tabela com dados de navegadores!
    private function BrowserUpdate() {
        $readAgent = new Read;
        $readAgent->ExeRead('siteviews_agent', "WHERE nome = :agent", "agent={$this->Browser}");
        if (!$readAgent->getResult()):
            $ArrAgent = ['nome' => $this->Browser, 'views' => 1];
            $createAgent = new Create;
            $createAgent->ExeCreate('siteviews_agent', $ArrAgent);
        else:
            $ArrAgent = ['views' => $readAgent->getResult()[0]['views'] + 1];
            $updateAgent = new Update;
            $updateAgent->ExeUpdate('siteviews_agent', $ArrAgent, "WHERE nome = :name", "name={$this->Browser}");
        endif;
    }

    /*
     * ***************************************
     * *********   USUÁRIOS ONLINE   *********
     * ***************************************
     */

    //Cadastra usuário online na tabela!
    private function setUsuario() {
        $sesOnline = $_SESSION['useronline'];
        $sesOnline['nome'] = $this->Browser;

        $userCreate = new Create;
        $userCreate->ExeCreate('siteviews_online', $sesOnline);
    }

    //Atualiza navegação do usuário online!
    private function UsuarioUpdate() {
        $ArrOnlone = [
            'endview' => $_SESSION['useronline']['endview'],
            'url' => $_SESSION['useronline']['url']
        ];

        $userUpdate = new Update;
        $userUpdate->ExeUpdate('siteviews_online', $ArrOnlone, "WHERE session = :ses", "ses={$_SESSION['useronline']['session']}");

        if (!$userUpdate->getRowCount()):
            $readSes = new Read;
            $readSes->ExeRead('siteviews_online', 'WHERE session = :onses', "onses={$_SESSION['useronline']['session']}");
            if (!$readSes->getRowCount()):
                $this->setUsuario();
            endif;
        endif;
    }

}