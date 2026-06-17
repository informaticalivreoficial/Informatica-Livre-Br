<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" href="{{route('web.home')}}" title="Ver Site" target="_blank"><i class="fas fa-desktop"></i></a>
        </li>
                
        <!-- Notifications Dropdown Menu -->
        <livewire:components.notifications-dropdown />

        @php
            if(!empty(auth()->user()->avatar) && \Illuminate\Support\Facades\Storage::exists(auth()->user()->avatar)){
                $cover = \Illuminate\Support\Facades\Storage::url(auth()->user()->avatar);
            } else {
                if(auth()->user()->gender == 'masculino'){
                    $cover = url(asset('theme/images/avatar5.png'));
                }elseif(auth()->user()->gender == 'feminino'){
                    $cover = url(asset('theme/images/avatar3.png'));
                }else{
                    $cover = url(asset('theme/images/image.jpg'));
                }
            }
        @endphp

        

        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link p-0" data-toggle="dropdown" href="#">
                <img
                    src="{{ $cover }}"
                    class="rounded-circle"
                    width="32"
                    height="32"
                    style="object-fit: cover;"
                >
            </a>

            <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                <a href="{{ route('users.edit', auth()->user()->id) }}" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i> Perfil
                </a>

                <a class="dropdown-item" style="cursor: pointer;" title="Financeiro">
                    <i class="fas fa-file-invoice mr-2"></i> Financeiro
                </a>

                <a 
                    href="#"
                    wire:click.prevent="$dispatch('open-support-modal')"
                    title="Suporte"
                    class="nav-link"
                >
                    <i class="fas fa-life-ring text-red-500 mr-2"></i> Ajuda
                </a>

                @auth
                    <livewire:auth.button-logout />
                @endauth
            </div>
        </li>
    </ul>
</nav>
