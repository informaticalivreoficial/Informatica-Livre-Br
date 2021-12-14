<div class="wrapper">
    <div class="mail-box">
        <form role="form-horizontal" action="" method="post" class="j_submitemail" enctype="multipart/form-data">
            <section class="mail-box-info">
                <header class="header">
                    <div class="row" style="margin-top: 20px;">
                        <div class="col-md-12">
                        <div class="alertas"></div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="compose-btn pull-right form_hide">
                        <button type="submit" class="btn btn-primary btn-sm b_nome"><i class="fa fa-check"></i> Enviar</button>
                    </div>
                    <div class="btn-toolbar form_hide">
                        <h4 class="pull-left">
                            E-mail
                        </h4>
                    </div>
                </header>

            <section class="mail-list form_hide">
                <div class="compose-mail">

                    <div class="form-group">
                        <label for="to" class="">Para:</label>
                        <input type="hidden" name="action" class="noclear" value="email_envia">
                        <?php
                        if(isset($_GET['email'])):
                            $getEmail = $_GET['email'];
                            echo '<input type="text" tabindex="1" name="email" class="form-control" value="'.$getEmail.'">';
                        else:
                            echo '<input type="text" tabindex="1" name="email" class="form-control">';
                        endif;
                        ?>
                        
                    </div>

                    <div class="form-group">
                        <label for="cc" class="">Cc:</label>
                        <input type="text" tabindex="2" name="cc" class="form-control"/>
                    </div>

                    <div class="form-group">
                        <label for="bcc" class="">Bcc:</label>
                        <input type="text" tabindex="2" name="bcc" class="form-control"/>
                    </div>

                    <div class="form-group">
                        <label for="subject" class="">Assunto:</label>
                        <input type="text" tabindex="1" id="subject" name="assunto" class="form-control"/>
                    </div>

                    <div class="compose-editor">
                        <textarea class="form-control editor noclear" name="mensagem" rows="9">                            
                                <p style="font-size:11px;text-align:left;">
                                <br /><br /><br />att<br />
                                <?= $userlogin['nome'];?><br />
                                <a title="<?= SITENAME;?>" href="<?= BASE;?>"><?= SITENAME;?></a><br />
                                <?= TELEFONE1;?> <?= TELEFONE2;?> 
                                <?php if(WATSAPP): echo 'WhatsApp: '.WATSAPP; endif;?><br />
                                </p>                            
                        </textarea>
                        <input type="file" name="arquivo" class="default" value=""/>
                    </div>

                </div>
            </section>
            </section>
        </form>  
    </div>
</div>