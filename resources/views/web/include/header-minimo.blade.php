
    <div class="rd-navbar-wrap">
      <nav data-md-device-layout="rd-navbar-fixed" data-lg-device-layout="rd-navbar-static" data-md-stick-up-offset="90px" data-lg-stick-up-offset="100px" data-auto-height="false" class="rd-navbar rd-navbar-top-panel rd-navbar-default rd-navbar-primary rd-navbar-static-fullwidth-transparent" data-lg-auto-height="true" data-md-layout="rd-navbar-fullwidth" data-lg-layout="rd-navbar-static" data-lg-stick-up="true">
        <div class="rd-navbar-inner">
          <!-- RD Navbar Top Panel-->
          <div class="rd-navbar-top-panel">
            <div class="left-side">
                <!-- Contact Info-->
                <address class="contact-info text-left">
                @if ($configuracoes->whatsapp)
                    <div class="reveal-inline-block">
                        <a href="{{getNumZap($configuracoes->whatsapp ,'Atendimento Aqua Marine Turismo Náutico')}}" class="unit unit-middle unit-horizontal unit-spacing-xs">
                            <span class="unit-left">
                                <span style="background-color: #34af23;color:#fff;" class="icon icon-xxs icon-circle mdi mdi-whatsapp"></span>
                            </span>
                            <span class="unit-body">
                                <span class="text-gray-darker">{{$configuracoes->whatsapp}}</span>
                            </span>
                        </a>
                    </div>
                @endif
                    
				@if($configuracoes->rua)	
                <div class="reveal-inline-block">
                    <a target="_blank" title="Localização" href="{{getLinkGoogleMaps($configuracoes->mapa_google)}}" class="unit unit-middle unit-horizontal unit-spacing-xs">
                        <span class="unit-left">
                            <span class="icon icon-xxs icon-primary icon-primary-filled icon-circle mdi mdi-map-marker"></span>
                        </span>						
                        <span class="unit-body">
                            <span class="text-gray-darker">{{$configuracoes->rua}}
							@if($configuracoes->num)
								, {{$configuracoes->num}}
								@if($configuracoes->bairro)
									, {{$configuracoes->bairro}}
								@endif
							@endif
							</span>
                        </span>						
                    </a>
                </div>
				@endif
                <!--
                <div class="reveal-inline-block">
                    <a style="background-color: #34af23;color:#fff;padding:8px;" href="">Minha Reserva</a>               
                </div>-->
                </address>
            </div>
            <div class="right-side">
                <ul class="list-inline list-inline-2">
                @if ($configuracoes->email)
                    <li><a href="mailto:{{$configuracoes->facebook}}" class="icon icon-xxs icon-silver-filled icon-circle fa fa-envelope"></a></li>
                @endif
                @if ($configuracoes->facebook)
                    <li><a target="_blank" href="{{$configuracoes->facebook}}" class="icon icon-xxs icon-silver-filled icon-circle fa fa-facebook"></a></li>
                @endif
                @if ($configuracoes->twitter)
                    <li><a target="_blank" href="{{$configuracoes->twitter}}" class="icon icon-xxs icon-silver-filled icon-circle fa fa-twitter"></a></li>
                @endif
                @if ($configuracoes->instagram)
                    <li><a target="_blank" href="{{$configuracoes->instagram}}" class="icon icon-xxs icon-silver-filled icon-circle fa fa-instagram"></a></li>
                @endif 
				@if(Session::has('cart'))
				<li style="color:#34af23;">
						<a style="font-size:1.7em;position:relative;" href="{{route('web.passeios.meucarrinho')}}" class="icon icon-xxs fa fa-shopping-cart"></a>
						<span style="padding: 3px;
    font-size: 12px;
    
    min-width: 17px;background-color: #21a9e1;
    color: #ffffff;border-radius: 20px;
    line-height: 12px;position:relative;top:-11px;left:-8px;">1</span>
					</li>
				@endif
                </ul>
            </div>
        </div>
          <!-- RD Navbar Panel-->
          <div class="rd-navbar-panel">
            <!-- RD Navbar Toggle-->
            <button data-rd-navbar-toggle=".rd-navbar, .rd-navbar-nav-wrap" class="rd-navbar-toggle"><span></span></button>
            <!-- RD Navbar Top Panel Toggle-->
            <button data-rd-navbar-toggle=".rd-navbar, .rd-navbar-top-panel" class="rd-navbar-top-panel-toggle"><span></span></button>
            <!-- Navbar Brand-->
            <div class="rd-navbar-brand">
				<a href="{{route('web.home')}}">
					<img src="{{$configuracoes->getlogomarca()}}" alt="{{$configuracoes->nomedosite}}" title="{{$configuracoes->nomedosite}}"/>
				</a>
			</div>
          </div>
          <div class="rd-navbar-menu-wrap">
            <div class="rd-navbar-nav-wrap">
              <div class="rd-navbar-mobile-scroll">
                <!-- Navbar Brand Mobile-->
                <div class="rd-navbar-mobile-brand">
					<a href="{{route('web.home')}}">
						<img src='{{$configuracoes->getlogomarca()}}' alt='{{$configuracoes->nomedosite}}'/>
					</a>
				</div>
                 <!--<div class="form-search-wrap">
                  
                  <form action="search-results.html" method="GET" class="form-search rd-search">
                    <div class="form-group">
                      <label for="rd-navbar-form-search-widget" class="form-label form-search-label form-label-sm rd-input-label">Search</label>
                      <input id="rd-navbar-form-search-widget" type="text" name="s" autocomplete="off" class="form-search-input input-sm form-control form-control-gray-lightest input-sm"/>
                    </div>
                    <button type="submit" class="form-search-submit"><span class="fa fa-search"></span></button>
                  </form>
                </div>-->
                <!-- RD Navbar Nav-->
                <ul class="rd-navbar-nav">
					<li class="active"><a href="{{route('web.home')}}">Início</a></li>
					<li class=""><a href="{{route('web.embarcacoes')}}">Embarcações</a></li>
					<li class=""><a href="{{route('web.roteiros')}}">Roteiros</a></li>
					<li class=""><a href="{{route('web.blog.artigos')}}">Dicas</a></li>
					<li class=""><a href="{{route('web.atendimento')}}">Atendimento</a></li>
				</ul>
              </div>
            </div>
            <!-- 
            <div class="rd-navbar-search rd-navbar-search-top-panel"><a data-rd-navbar-toggle=".rd-navbar-inner,.rd-navbar-search" href="#" class="rd-navbar-search-toggle mdi"><span></span></a>
				<form action="#" data-search-live="rd-search-results-live" method="GET" class="rd-navbar-search-form search-form-icon-right rd-search">
				<div class="form-group">
					<label for="rd-navbar-search-form-input" class="form-label rd-input-label">Pesquise no site...</label>
					<input id="rd-navbar-search-form-input" type="text" name="s" autocomplete="off" class="rd-navbar-search-form-input form-control form-control-gray-lightest"/>
				</div>
				<div id="rd-search-results-live" class="rd-search-results-live"></div>
				</form>
			</div>-->
          </div>
        </div>
      </nav>
    </div>
    <!-- Swiper-->
    <div data-height="120px" data-min-height="120px" data-slide-effect="fade" data-simulate-touch="false" data-loop="false" class="swiper-container swiper-slider swiper-slider-offset-top-inverse context-dark text-lg-left"></div>