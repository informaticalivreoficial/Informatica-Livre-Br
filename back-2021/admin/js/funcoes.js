$(function () {
    
    // FUNÇÃO MODAL DE CARREGAMENTO DO SISTEMA
    $(window).ready(function(){
        $('.loadsistem').fadeOut("fast",function(){
            $('.dialog').fadeOut("fast");
        });
    });
    
    //VARIÁVEIS GERAIS
    var url = 'ajax/ajax.php';
    
    $('.j_loadstate').change(function() {
        var uf = $('.j_loadstate');
        var city = $('.j_loadcity');
        var patch = ($('#j_ajaxident').length ? $('#j_ajaxident').attr('class') + '/cidades.php' : 'ajax/cidades.php');

        city.attr('disabled', 'true');
        uf.attr('disabled', 'true');

        city.html('<option value=""> Carregando cidades... </option>');

        $.post(patch, {estado: $(this).val()}, function(cityes) {   
            city.html(cityes).removeAttr('disabled');
            uf.removeAttr('disabled');
        });
    });
    
    // FUNÇÃO CARREGA SUBCATEGORIAS
    $('.j_loadcat').change(function() {
        var cat    = $('.j_loadcat');
        var subcat = $('.j_loadsubcat');
        var ajaxdata   = 'ajax/ajax-sub-categorias.php';
        
        subcat.attr('disabled', 'true');
        cat.attr('disabled', 'true');
        
        subcat.html('<option value=""> Carregando... </option>');
        
        $.post(ajaxdata, {cat_pai: $(this).val()}, function(subcats) {   
            subcat.html(subcats).removeAttr('disabled');
            cat.removeAttr('disabled');
        });
    });
    
    // FUNÇÃO FILTRO CLIENTES
    $('.j_alfabeto').click(function() {
        var id_task = $(this).attr('data-id');
        var url = 'ajax/filtro-clientes.php';
        
        $.ajax({
            url: url,
            type: 'POST',
            data: {id:id_task},
                        
            beforeSend: function(){
                $('.hideR').fadeOut("fast");
            },

            success: function(callback){
                $('.resultado').html(callback);
            }
       });        
    });


    // FUNÇÃO ALTERAR SENHA DO USUÁRIO NO PAINEL
    $('.j_submit').submit(function(){
        var form = $(this);
        var data = $(this).serialize();

        $.ajax({
            url: url,
            data: data,
            type: 'POST',
            dataType: 'json',
            beforeSend: function(){
                $('#b_nome').html("Carregando...");
                $('.alert').fadeOut(500, function(){
                    $(this).remove();
                });
            },
            complete: function(){
                $('#b_nome').html("Enviar");
            },
            success: function(resposta){
                if(resposta.error){
                    $('.alertas').html('<div class="alert alert-danger">' + resposta.error + '</div>');
                    $('.alert-danger').fadeIn();
                }else{
                    $('.alertas').html('<div class="alert alert-success">' + resposta.success + '</div>');
                    $('.alert-success').fadeIn();
                }
            },
            error: function(){
                $('.alertas').empty().html('<div class="alert alert-danger"><strong>Erro No Sistema!</strong></div>').fadeIn('slow');
            }

        });

        return false;
    });


    // FUNÇÃO ENVIO DE E-MAIL PELO PAINEL
    $('.j_submitemail').submit(function(){
        var form = $(this);
        var data = $(this).serialize();

        $(this).ajaxSubmit({
            url: url,
            //data: data,
            //type: 'POST',
            //enctype: 'multipart/form-data',
            dataType: 'json',


            beforeSend: function(){
                form.find('.b_nome').html("Carregando...");
                form.find('.alert').fadeOut(500, function(){
                    form.find(this).remove();
                });
            },
            complete: function(){
                form.find('.b_nome').html("Enviar");
            },
            success: function(resposta){
                //console.log(resposta);
                $('html, body').animate({scrollTop:0}, 'slow');
                if(resposta.error){
                    $('.alertas').html('<div class="alert alert-danger"> ' + resposta.error + '</div>');
                    $('.alert-danger').fadeIn();
                }else{
                    $('.alertas').html('<div class="alert alert-success"> ' + resposta.success + '</div>');
                    $('.alert-success').fadeIn();
                    $('input[class!="noclear"]').val('');
                    //form.find('.form_hide').fadeOut(500);
                }
            },
            error: function(){
                $('.alertas').empty().html('<div class="alert alert-danger"><strong>Erro No Sistema!</strong></div>').fadeIn('slow');
            }

        });

        return false;
    });
    

    // FUNÇÃO ENVIO DE  SENHA POR E-MAIL PELO PAINEL
    $('.j_recuperasenha').submit(function(){ 
        var form = $(this);
        var data = $(this).serialize();
        
        $.ajax({
            url: url,
            data: data,
            type: 'POST',
            dataType: 'json',            
            
            beforeSend: function(){
                form.find('.phidenn').fadeOut(500);
                form.find('.b_nome').html("Carregando...");
                form.find('.alert').fadeOut(500, function(){
                    $(this).remove();
                });
            },
            complete: function(){
                form.find('.b_nome').html("Recuperar Senha");               
            },
            success: function(resposta){
                //$('html, body').animate({scrollTop:0}, 'slow');
                if(resposta.error){
                    form.find('.alertas').html('<div class="alert alert-danger"> ' + resposta.error + '</div>');
                    form.find('.alert-danger').fadeIn();
                }else{
                    form.find('.alertas').html('<div class="alert alert-success"> ' + resposta.success + '</div>');
                    form.find('.alert-success').fadeIn();
                    form.find('input[class!="noclear"]').val('');
                    form.find('.form_hide').fadeOut(500);
                }
            },
            error: function(){
                form.find('.alertas').empty().html('<div class="alert alert-danger"><strong>Erro No Sistema!</strong></div>').fadeIn('slow');
            }
            
        }); 
               
        return false;
    });
    
});