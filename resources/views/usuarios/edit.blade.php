@extends('plantillas.principal')

@section('contenido')
<div class="container">
	<div class="col-md-8 col-md-offset-2">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h4>Editar Perfil</h4>
			</div>
			<div class="panel-body">
				<ul class="nav nav-pills nav-justified">
					<li class="active"><a href="#tab1" data-toggle="tab">Datos Usuario</a></li>
					<li><a href="#tab2" data-toggle="tab">Cambiar Contraseña</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane fade active in" id="tab1">
						{!! Form::model($usuario, [
							'route' => ['usuarios.update', $usuario->id_usuario],
							'method' => 'put',
							'files' => true,
							'class' => 'form-horizontal'
							]) !!}
							@if(Auth::user()->tipo === 'administrador')
							@include('usuarios.form-edit')
							@endif
							@if(Auth::user()->tipo === 'autorizado' || Auth::user()->tipo === 'docente')
							@include('usuarios.form-edit-perfil')
							@endif
							<div class="text-center">
								<button type="submit" class="btn btn-primary">
									<i class="material-icons">done</i> Actualizar
								</button>
							</div>
						{!! Form::close() !!}
					</div>
					<div class="tab-pane fade" id="tab2">
					{!! Form::model($usuario, ['route' => ['usuarios.update', $usuario->id_usuario], 'method' => 'put', 'class' => 'form-horizontal']) !!}
						<div class="form-group">
							{!! Form::label('password', 'Contraseña actual', ['class' => 'control-label col-md-3']) !!}
							<div class="col-md-8">
								{!! Form::password('password', ['class' => 'form-control']) !!}
							</div>
						</div>
						<div class="form-group">
							{!! Form::label('password_nuevo', 'Contraseña nueva', ['class' => 'control-label col-md-3']) !!}
							<div class="col-md-8">
								{!! Form::password('password_nuevo', ['class' => 'form-control']) !!}
							</div>
						</div>
						<div class="form-group">
							{!! Form::label('password_nuevo_confirm', 'Confirmar contraseña nueva', ['class' => 'control-label col-md-3']) !!}
							<div class="col-md-8">
								{!! Form::password('password_nuevo_confirm', ['class' => 'form-control']) !!}
							</div>
						</div>
						<div class="text-center">
							<button type="submit" class="btn btn-primary">
								<i class="material-icons">done</i> Actualizar
							</button>
						</div>
					</div>
					{!! Form::close() !!}
				</div>
						
					
			</div>
		</div>
	</div>
</div>
@endsection