
<!--logo and iconic logo start-->
<div class="logo">
    <a href="painel.php"><img src="../uploads/<?= LOGOMARCAADMIN;?>" width="50%" alt="<?= SITENAME;?>" title="<?= SITENAME;?>"/></a>
</div>

<div class="logo-icon text-center">
    <a href="painel.php"><img src="../uploads/<?= LOGOMARCAADMIN;?>" width="50%" alt="<?= SITENAME;?>" title="<?= SITENAME;?>"/></a>
</div>
<!--logo and iconic logo end-->

<div class="left-side-inner">
    <?php $actionUrl = explode('/',$getexe); ?>
    <!--sidebar nav start-->
    <ul class="nav nav-pills nav-stacked custom-nav">
        <li <?php if(in_array('',$actionUrl)){echo 'class="active"';}?>><a href="painel.php"><i class="fa fa-home"></i> <span>Painel de Controle</span></a></li>
        <?php
           //atribuição a variável $dir
           $dir = new DirectoryIterator( '../admin/componentes/' );
           
           //atribui o valor de $dir para $file em loop 
           foreach($dir as $file):
              //verifica se o valor de $file é diferente de '.' ou '..'
              //e é um diretório (isDir)
             if (!$file->isDot() && $file->isDir()){
        	 //atribuição a variável $dname
                $dname = $file->getFilename();
                // abre o arquivo xml
                $menu = simplexml_load_file('../admin/componentes/'.$dname.'/menu.xml');
                // faz o loop no item xml index1
                       
                foreach($menu->index1 as $menulist):
                
                $permissao = $menulist->permissao;
                if($userlogin['nivel'] == '1'){             
                      
                      if($menu->index){// se tiver submenus 
                          echo '<li class="menu-list';
                          if(in_array($dname,$actionUrl)){echo ' nav-active';}
                          echo '">';               
                          echo '<a href="#">';
                          echo '<i class="'.$menulist->icon.'"></i>';
                          echo '<span>'.$menulist->label.'</span>';
                          echo '</a>';
                            echo '<ul class="sub-menu-list">';
                            
                            foreach($menu->index as $submenulist):
                            echo '<li';
                            if($actionUrl[0] == $submenulist->link){echo ' class="active"';}
                            echo '>';
                            echo '<a href="painel.php?exe='.$dname.'/'.$submenulist->link.'">';
                            echo '<span>'.$submenulist->label.'</span>';
                            echo '</a>';
                            echo '</li>';
                            endforeach;
                            echo '</ul>';
                            echo '</li>';
                      }elseif($menu->index1){// se não tiver submenus
                          echo '<li class="';
                          if(in_array($dname,$actionUrl)){echo 'active';}
                          echo '">';
                          echo '<a href="painel.php?exe='.$dname.'/'.$menulist->link.'">';
                          echo '<i class="'.$menulist->icon.'"></i>';
                          echo '<span>'.$menulist->label.'</span>';
                          echo '</a>';
                          echo '</li>';
                      }            
                      
                }elseif($userlogin['nivel'] == '2' && $permissao == '2'){
                    echo '<li class="menu-list">';
                      if($menu->index){// se tiver submenus              
                          echo '<a href="#">';
                          echo '<i class="'.$menulist->icon.'"></i>';
                          echo '<span>'.$menulist->label.'</span>';
                          echo '</a>';
                            echo '<ul class="sub-menu-list">';
                            foreach($menu->index as $submenulist):
                            echo '<li>';
                            echo '<a href="painel.php?exe='.$dname.'/'.$submenulist->link.'">';
                            echo '<span>'.$submenulist->label.'</span>';
                            echo '</a>';
                            echo '</li>';
                            endforeach;
                            echo '</ul>';
                      }else{// se não tiver submenus
                          echo '<a href="painel.php?exe='.$dname.'/'.$menulist->link.'">';
                          echo '<i class="'.$menulist->icon.'"></i>';
                          echo '<span>'.$menulist->label.'</span>';
                          echo '</a>';
                      }            
                      echo '</li>';
                }      
                endforeach;         
             }
           endforeach;
         ?>

    </ul>
    <!--sidebar nav end-->
</div>