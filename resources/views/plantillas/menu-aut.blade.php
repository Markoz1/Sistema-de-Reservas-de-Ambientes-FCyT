<nav class="navbar navbar-default">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" 
				class="navbar-toggle collapsed" 
				data-toggle="collapse" 
				data-target="#bs-navbar-collapse-1" 
				aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand hidden-xs" href="{{ route('principal.inicio') }}">Inicio<!-- <img alt="Inicio" src="{!! asset('img/logo1.png') !!}"style = "max-height:35px;"> --></a>
			<a class="navbar-brand visible-xs" href="{{ route('principal.inicio') }}">Sistema de Reservas</a>
		</div>
		<div class="collapse navbar-collapse" id="bs-navbar-collapse-1">
			<ul class="nav navbar-nav">
			<li><a href="{{ route('reservas.index') }}">Reservas</a></li>
			@yield('menu')
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="#" 
						class="dropdown-toggle" 
						data-toggle="dropdown" 
						role="button" 
						aria-haspopup="true" 
						aria-expanded="false">
						<i class="material-icons">account_circle</i>
						&nbsp;{{ Auth::user()->username }}&nbsp;
						<span class="caret"></span>
					</a>
		          	<ul class="dropdown-menu">
		          		<li class="visible-xs">
		            		<a href="{{ route('usuarios.perfil') }}">
		            			Perfil
		            		</a>
		            	</li>
		            	<li>
		            		<a href="{{ route('usuarios.logout') }}">
		            			Cerrar sesión
		            		</a>
		            	</li>
		          	</ul>
		        </li>
			</ul>
		</div>
	</div>
</nav>