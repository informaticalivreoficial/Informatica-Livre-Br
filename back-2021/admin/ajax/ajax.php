<?php
    $getPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    //$setPost = array_map('strip_tags', $getPost);
    $Post    = $getPost;
    
    $Action = $Post['action'];
    $jSon = array();
    unset($Post['action']);    
    //sleep(2);
    
    if($Action):
        require('../../vendor/autoload.php');
        require('../../app/config.inc.php');
    endif;
    
    switch ($Action):
            //ENVIAR E-MAIL PELO PAINEL
            case'email_envia':
                if(!Check::Email($Post['email'])):
                    $jSon['error'] = "O campo <strong>E-mail</strong> está vazio ou tem um formato inválido!";
                elseif($Post['assunto'] == ''):
                    $jSon['error'] = "O campo <strong>Assunto</strong> está vazio!";
                else:
                    require('../../app/Library/PHPMailer/class.phpmailer.php');
                    require('../../app/Models/Email.class.php');
                    
                    $data  = date('d/m/Y');
                    
                    $Contato['RemetenteNome']  = SITENAME;
                    $Contato['RemetenteEmail'] = MAILUSER;
                    $Contato['Assunto']        = $Post['assunto'];
                    $Contato['DestinoNome']    = $Post['email'];
                    $Contato['DestinoEmail']   = $Post['email'];                
                    $Contato['Mensagem']       = $Post['mensagem'];
                    
                    
                    if($Post['cc'] != '' && Check::Email($Post['cc'])):
                        $Contato['DestinoCopia'] = $Post['cc'];
                    else:
                        $Contato['DestinoCopia'] = null;
                    endif;
                    if($Post['bcc'] != '' && Check::Email($Post['bcc'])):
                        $Contato['DestinoCCopia'] = $Post['bcc'];
                    else:
                        $Contato['DestinoCCopia'] = null;
                    endif;
                    if(!empty($_FILES['arquivo']['tmp_name'])):
                        $Contato['DestinoArquivo'] = $_FILES['arquivo']['tmp_name'];
                        $Contato['DestinoArquivoNome'] = $_FILES['arquivo']['name'];
                    else:
                        $Contato['DestinoArquivo'] = null;
                        $Contato['DestinoArquivoNome'] = null;
                    endif;
                    
                    $SendMail = new Email;
                    $SendMail->Enviar($Contato);
                    $jSon['success'] = "O e-mail foi enviado com sucesso!";
                endif;
            break;
        
            //ALTERA SENHA DO USUÁRIO NO PAINEL
            case'alterar_senha':
            
                $userId = $Post['iduser'];
                unset($Post['iduser']);
                
                if($Post['senha'] == '' && $Post['code'] == ''):
                    $jSon['error'] = "<strong>Erro:</strong> preencha os dois campos!";
                elseif($Post['senha'] != $Post['code']):
                    $jSon['error'] = "<strong>Erro:</strong> As duas senhas tem que ser iguais!";
                elseif(strlen($Post['code']) < 8 || strlen($Post['code']) > 12):
                    $jSon['error'] = "<strong>Erro!</strong> Sua senha deve conter entre 8 e 12 caracteres!";
                else:
                    require('../models/AdminUser.class.php');
                    $atualiza = new AdminUser;
                    $atualiza->ExeUpdateSenha($userId, $Post);

                    if($Post['tipo'] == '1'):
                        $tipo = '<strong>Sucesso!</strong> Senha Alterada!';
                    elseif($Post['tipo'] == '2'):
                        $tipo = '<strong>Sucesso!</strong> Senha Alterada!';
                    elseif($Post['tipo'] == '3'):
                        $tipo = '<strong>Sucesso!</strong> Senha Alterada!';
                    endif;


                    
                    //$result = RMErro($atualiza->getError()[0], $atualiza->getError()[1]);
                    $jSon['success'] =   "$tipo";
                    
                endif;                                
            break;
            
            //RECUPERAR SENHA DO USUÁRIO NO PAINEL
            case'recuperar_senha':
                if(!Check::Email($Post['email'])):
                    $jSon['error'] = "O campo <strong>E-mail</strong> está vazio ou tem um formato inválido!";
                elseif(!empty($Post['bairro']) || !empty($Post['cidade'])):
                    $jSon['error'] = "<strong>ERRO</strong> Você está praticando SPAM!";
                else:    
                    require('../../app/Library/PHPMailer/class.phpmailer.php');
                    require('../../app/Models/Email.class.php');
                    $data  = date('d/m/Y');
                    
                    $ReadUser = new Read;
                    $ReadUser->ExeRead("usuario", "WHERE email = :emailUser", "emailUser={$Post['email']}");
                    if($ReadUser->getResult()):
                        $usuario = $ReadUser->getResult()['0'];
                    endif;
                    
                    $Contato['RemetenteNome']  = SITENAME;
                    $Contato['RemetenteEmail'] = MAILUSER;
                    $Contato['Assunto']        = '#Recuperação de Senha';
                    $Contato['DestinoNome']    = $usuario['nome'];
                    $Contato['DestinoEmail']   = $usuario['email'];

                    $Contato['Mensagem'] = '<body style="background:#E9ECEF; margin:0; padding:0;">
                                    <div style="width:100%;">        
                                        <div id="box-header" style="background:#ffefa4; overflow:hidden; padding:15px;">                        
                                            <div style="float:left; font:20px Trebuchet MS, Arial, Helvetica, sans-serif; color:#574802; font-weight:bold; text-align:right;">
                                                Mensagem enviada pelo site '.SITENAME.'
                                            </div>

                                            <div style="float:right; font:16px Trebuchet MS, Arial, Helvetica, sans-serif; color:#574802; font-weight:bold;">
                                                Data '.$data.'
                                            </div>                        
                                        </div>

                                        <div id="box-content" style="background:#FFF; padding:10px; font:16px Trebuchet MS, Arial, Helvetica, sans-serif; color:#333; line-height:150%;">       
                                            <h1 style="font-size:20px; color:#000; background:#F4F4F4; padding:10px;">Olá '.Check::getPrimeiroNome($usuario['nome']).' recupere seu acesso!</h1>
                                            <p>
                                            Estamos entrando em contato pois foi solicitado em nosso site a recuperação de dados de acesso. Verifique abaixo os dados de seu usuário:
                                            <br />
                                            <strong>E-mail: </strong>'.$usuario['email'].' - <strong>Senha: </strong>'.$usuario['code'].'
                                            <br />                                            
                                            </p>
                                            <p>Recomendamos que você altere seus dados em seu perfil após efetuar o login!</p>
                                            <br />
                                            <p><strong>Painel Administrativo: </strong><a href="'.BASE.'/admin">Clique aqui para acessar</a></p>
                                        </div> 
                                    </div>
                                    <div style="width:100%; margin:20px 0; text-align:center; font-size:10px;"><pre>Sistema de consultas desenvolvido por Informática Livre - <a href="mailto:suporte@informaticalivre.com">suporte@informaticalivre.com</a></pre></div>
                                </body>                  
                                ';
                    
                    $Contato['DestinoCopia'] = null;
                    $Contato['DestinoCCopia'] = null;
                    $Contato['DestinoArquivo'] = null;
                    $Contato['DestinoArquivoNome'] = null;
                                        
                    $SendMail = new Email;
                    $SendMail->Enviar($Contato);
                    
                    $jSon['success'] =   "Sucesso, seus dados de acesso foram enviados para seu e-mail!"; 
                endif;
            break;
            
            
            default:
               $jSon['error'] = "Erro ao selecionar a ação!";
    endswitch;
    
    echo json_encode($jSon);