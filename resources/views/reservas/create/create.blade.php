@extends('reservas.principal')

@section('contenido-principal')
<div class="">
    <div class="panel-body">
        {!! Form::open([
			'route' => ['reservas.horarios'],
		    'method' => 'get',
			'files' => true,
            'class' => 'form-horizontal'
			]) !!}

            <div class="form-group col-md-6">
                {!! Form::label('ambiente', 'Ambiente', [
                    'class' => 'control-label col-md-4'
                ]) !!}
                <div class="col-md-7 col-md-offset-1">
                    {!! Form::select('ambiente', ['1' => 'Auditorio',],
                    null,
                    ['class' => 'form-control', 'onchange' => "this.form.submit()"])
                    !!}
                </div>
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('fecha', 'Fecha', ['class' => 'control-label col-md-4']) !!}
                <div class="col-md-7 col-md-offset-1"">
                    {{--{!! Form::text('nombre', null, ['class' => 'form-control']) !!}--}}
                    @if(empty($fecha))
                        {!! Form::date('fecha', null, ['class' => 'form-control', 'onchange' => "this.form.submit()"]) !!}
                    @else
                        {!! Form::date('fecha', $fecha, ['class' => 'form-control', 'onchange' => "this.form.submit()"]) !!}
                    @endif
                </div>
            </div>
        {!! Form::close() !!}      
    </div>
    @yield('contenido-create')
</div>
@endsection
@section('panel-footer')
<div class="btn-group btn-group-justified">
    <div class="col-md-2 col-md-offset-10">
        <div class="btn-group">
            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#formularioReserva">Siguiente</button>
        </div>
    </div>
</div>
    @include('reservas.formularios.form')
@endsection