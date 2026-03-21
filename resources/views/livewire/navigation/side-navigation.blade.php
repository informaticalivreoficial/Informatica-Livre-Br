<aside class="main-sidebar sidebar-light-teal elevation-4">
    <!-- Brand Logo -->
    <a class="pt-3 d-flex justify-content-center cursor-pointer">
        <img src="{{$config->getlogoadmin()}}" alt="{{$config->app_name}}"
            class="brand-image elevation-3" width="147" height="53">        
    </a>

    <div class="sidebar mt-3">
        {{-- Sidebar user panel (optional)  --}}
        {{-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('assets/bms_logo.png') }}" class="img-circle elevation-2" alt="User Image">
            </div>

            <div class="info">
                <a href="#" class="d-block">Alexander Pierce</a>
            </div>
        </div> --}}

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{route('admin')}}" class="nav-link {{ Route::is('admin') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p> Painel de Controle</p>
                    </a>                    
                </li>

                <li class="nav-item">
                    <a href="{{route('settings')}}" class="nav-link {{ Route::is('settings') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cog"></i> 
                        <p> Configurações</p>
                    </a>
                </li>

                <li class="nav-item {{ Route::is('users.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Route::is('users.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p> Usuários <i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('users.index')}}" class="nav-link {{ Route::is('users.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Clientes <span class="badge badge-info right">{{--$clientCount--}}</span></p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('users.time')}}" class="nav-link {{ Route::is('users.time') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Time <span class="badge badge-info right">{{--$timeCount--}}</span></p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('users.create')}}" class="nav-link {{ Route::is('users.create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Cadastrar Novo</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @role(['super-admin', 'manager'])
                    <li class="nav-item">
                        <a href="{{route('companies.index')}}" class="nav-link {{ Route::is('companies.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-industry"></i>
                            <p>Empresas</p>
                        </a>
                    </li>
                @endrole
                <li class="nav-item {{ Route::is('services.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Route::is('services.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-store"></i>
                        <p> Serviços <i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('services.index')}}" class="nav-link {{ Route::is('services.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Listar Serviços</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('services.subscriptions.index')}}" class="nav-link {{ Route::is('services.subscriptions.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pedidos <span class="badge badge-info right">{{--$timeCount--}}</span></p>
                            </a>
                        </li>                        
                    </ul>
                </li>  
                <li class="nav-item {{ Route::is('portifolio.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Route::is('portifolio.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-project-diagram"></i>
                        <p> Portifólio <i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('portifolio.index')}}" class="nav-link {{ Route::is('portifolio.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Listar Todos</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('portifolio.categories.index')}}" class="nav-link {{ Route::is('portifolio.categories.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Categorias</p>
                            </a>
                        </li>                        
                    </ul>
                </li>  
                <li class="nav-item">
                    <a href="{{route('slides.index')}}" class="nav-link {{ Route::is('slides.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-film"></i>
                        <p>Banners</p>
                    </a>
                </li>  
                <li class="nav-item {{ Route::is('posts.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Route::is('posts.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-pencil-alt"></i>
                        <p>Posts <i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('posts.index')}}" class="nav-link {{ Route::is('posts.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Listar Todos
                                    <span class="badge badge-info right">{{--$postsCount--}}</span>
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('posts.categories.index')}}" class="nav-link {{ Route::is('posts.categories.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Categorias</p>
                            </a>
                        </li>
                    </ul>
                </li>  
                    
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p> Relatórios <i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Relatório de Viagens</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{--route('manifestReport.index')--}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Relatório de Manifestos</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Relatório de Clientes</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{--route('companyReport.index')--}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Relatório de Empresas</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route('safes.index') }}" class="nav-link">
                        <i class="fas fa-lock"></i> 
                        <p>Cofre</p>
                    </a>
                </li>
                 
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-shield-alt"></i>
                        <p> Segurança <i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('admin.roles')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Cargos</p>
                            </a>
                        </li>                        
                        <li class="nav-item">
                            <a href="{{route('admin.permissions')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Permissões</p>
                            </a>
                        </li>                
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>
