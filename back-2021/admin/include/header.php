<!--toggle button start-->
<a class="toggle-btn"><i class="fa fa-bars"></i></a>
<!--toggle button end-->



<!--notification menu start -->
<div class="menu-right">

    <ul class="notification-menu">
        
        <li>
            <a class="btn btn-default info-number" href="../" title="Visualizar Site" target="_blank">
            <i class="fa fa-desktop"></i>            	
            </a>
        </li>
        
        <li style="margin-right: 10px;">            
            <a class="btn btn-default info-number tooltips" href="#" data-original-title="On-line Agora" data-toggle="tooltip " data-placement="bottom" style="padding: 12px 0px 12px 15px;border: none !important;">
            <i class="fa fa-users"></i>
            </a>
            <span class="badge badge-info"><?= Check::UserOnline();?></span>
        </li>
        
       <!-- <li>
        <a href="#" class="btn btn-default dropdown-toggle info-number" data-toggle="dropdown">
            <i class="fa fa-bell-o"></i>
            <span class="badge">4</span>
        </a>
        <div class="dropdown-menu dropdown-menu-head pull-right">
            <h5 class="title">Notificações</h5>
            <ul class="dropdown-list normal-list">
                
                <li class="new">
                    <a href="">
                        <span class="label label-danger"><i class="fa fa-bolt"></i></span>
                        <span class="name">Server #1 overloaded.  </span>
                        <em class="small">34 mins</em>
                    </a>
                </li>
                <li class="new">
                    <a href="">
                        <span class="label label-danger"><i class="fa fa-bolt"></i></span>
                        <span class="name">Server #3 overloaded.  </span>
                        <em class="small">1 hrs</em>
                    </a>
                </li>
                <li class="new">
                    <a href="">
                        <span class="label label-danger"><i class="fa fa-bolt"></i></span>
                        <span class="name">Server #5 overloaded.  </span>
                        <em class="small">4 hrs</em>
                    </a>
                </li>
                <li class="new">
                    <a href="">
                        <span class="label label-danger"><i class="fa fa-bolt"></i></span>
                        <span class="name">Server #31 overloaded.  </span>
                        <em class="small">4 hrs</em>
                    </a>
                </li>
                <li class="new"><a href="">See All Notifications</a></li>
            </ul>
        </div>
    </li>-->
        
                               
        <li>
            <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                <?php
                    if($userlogin['avatar'] != null):
                        echo Check::Image('../uploads/' . $userlogin['avatar'], $userlogin['nome'],'26','26');
                    else:
                        echo "<img src=\"images/image.jpg\" title=\"{$userlogin['nome']}\" />";
                    endif;
                ?>
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                <li><a href="painel.php?exe=usuarios/usuarios-perfil&userid=<?= $userlogin['id'];?>"><i class="fa fa-user"></i>  Perfil</a></li>
                <li><a href="painel.php?exe=usuarios/usuarios-edit&userid=<?= $userlogin['id'];?>"><i class="fa fa-pencil"></i>  Editar Perfil</a></li>
                <li><a href="painel.php?logoff=true"><i class="fa fa-sign-out"></i> Sair</a></li>
            </ul>
        </li>

    </ul>
</div>
<!--notification menu end -->