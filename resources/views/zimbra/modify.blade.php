@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Modificaci&oacute;n de Clave de Correo</div>

				<div class="panel-body">
					<!--{!! Form::open(['url' => 'soap-zimbra', 'role' => 'form', 'class' => 'form-horizontal']) !!}-->
					{!! Form::open(['route' => 'correoZimbra.store', 'method' => 'POST', 'role' => 'form', 'class' => 'form-horizontal']) !!}
					
					<!-- Cuenta de correo-->
						<div class="form-group{{ $errors->has('cuenta') ? ' has-error' : '' }}">
							{!! Form::label('cuenta', 'Direcci&oacute;n de Correo', ['class' => 'col-md-4 control-label']) !!}
							<div class="col-md-3">
								{!! Form::text('cuenta', old('cuenta'),['class' => 'form-control']) !!}
							</div>
							<div class="col-md-3">
								{!! config('zimbrasoap.arroba') !!} {!!config('zimbrasoap.dominio') !!}
								
								@if ($errors->has('cuenta'))
									<span class="help-block">
										<strong>{{ $errors->first('cuenta') }}</strong>
									</span>
								@endif
							</div>
						</div>

					<!-- Clave-->
						<div class="form-group{{ $errors->has('clave') ? ' has-error' : '' }}">
							{!! Form::label('clave', 'Contrase&ntilde;a', ['class' => 'col-md-4 control-label']) !!}
							<div class="col-md-3">
								<!--{!! Form::password('clave', ['class' => 'form-control']) !!}-->
								<input name='clave' type="password" class='form-control' />
								
								@if ($errors->has('clave'))
									<span class="help-block">
										<strong>{{ $errors->first('clave') }}</strong>
									</span>
								@endif
							</div>
						</div>
					
					<!-- Confirmacion de Clave-->
						<div class="form-group{{ $errors->has('clave_confirmation') ? ' has-error' : '' }}">
							{!! Form::label('clave_confirmation', 'Confirmar Contrase&ntilde;a', ['class' => 'col-md-4 control-label']) !!}
							<div class="col-md-3">
								<!--{!! Form::password('confirmar_clave', ['class' => 'form-control']) !!}-->
								<input name='clave_confirmation' type="password" class='form-control' />
								
								@if ($errors->has('clave_confirmation'))
									<span class="help-block">
										<strong>{{ $errors->first('clave_confirmation') }}</strong>
									</span>
								@endif
							</div>
						</div>
								
					<!-- Boton de aceptar-->
						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									<i class="fa fa-btn fa-plus-circle"></i> Guardar
								</button>
								<input type="hidden" name="opcion" value="cambiar_clave"/>
							</div>
						</div>

					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
