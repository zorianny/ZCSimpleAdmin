@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Creaci&oacute;n de Cuenta de Correo</div>

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

					<!-- Nombre -->
						<div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
							{!! Form::label('nombre', 'Nombre', ['class' => 'col-md-4 control-label']) !!}
							<div class="col-md-6">
								{!! Form::text('nombre', old('v'),['class' => 'form-control']	)!!}
									
								@if ($errors->has('nombre'))
									<span class="help-block">
										<strong>{{ $errors->first('nombre') }}</strong>
									</span>
								@endif
							</div>
					</div>
					
					<!-- Apellido -->
						<div class="form-group{{ $errors->has('apellido') ? ' has-error' : '' }}">
							{!! Form::label('apellido', 'Apellido', ['class' => 'col-md-4 control-label']) !!}
							<div class="col-md-6">
								{!! Form::text('apellido', old('apellido'),['class' => 'form-control']	)!!}
									
								@if ($errors->has('apellido'))
									<span class="help-block">
										<strong>{{ $errors->first('apellido') }}</strong>
									</span>
								@endif
							</div>
						</div>
					
					<!-- Cedula -->
						<div class="form-group{{ $errors->has('cedula') ? ' has-error' : '' }}">
							{!! Form::label('cedula', 'C&eacute;dula de Identidad', ['class' => 'col-md-4 control-label']) !!}
							<div class="col-md-6">
								{!! Form::text('cedula', old('cedula'),['class' => 'form-control']	)!!}
									
								@if ($errors->has('cedula'))
									<span class="help-block">
										<strong>{{ $errors->first('cedula') }}</strong>
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
					
					<!-- Empresa -->
						<div class="form-group{{ $errors->has('direccion') ? ' has-error' : '' }}">
							{!! Form::label('direccion', 'Direcci&oacute;n de Adscripci&oacute;n', ['class' => 'col-md-4 control-label']) !!}
							<div class="col-md-6">
								{!! Form::text('direccion', old('direccion'),['class' => 'form-control']	)!!}
									
								@if ($errors->has('direccion'))
									<span class="help-block">
										<strong>{{ $errors->first('direccion') }}</strong>
									</span>
								@endif
							</div>
						</div>
					
					<!-- Titulo Profesional -->
						<div class="form-group{{ $errors->has('profesion') ? ' has-error' : '' }}">
							{!! Form::label('profesion', 'T&iacute;tulo Profesional', ['class' => 'col-md-4 control-label']) !!}
							<div class="col-md-6">
								{!! Form::text('profesion', old('profesion'),['class' => 'form-control']	)!!}
									
								@if ($errors->has('profesion'))
									<span class="help-block">
										<strong>{{ $errors->first('profesion') }}</strong>
									</span>
								@endif
							</div>
						</div>
					
					<!-- Pais -->
						<div class="form-group{{ $errors->has('pais') ? ' has-error' : '' }}">
							{!! Form::label('pais', 'Pa&iacute;s', ['class' => 'col-md-4 control-label']) !!}
							<div class="col-md-6">
								{!! Form::text('pais', old('pais'),['class' => 'form-control']	)!!}
									
								@if ($errors->has('pais'))
									<span class="help-block">
										<strong>{{ $errors->first('pais') }}</strong>
									</span>
								@endif
							</div>
						</div>
					
					<!-- Estado -->
						<div class="form-group{{ $errors->has('estado') ? ' has-error' : '' }}">
							{!! Form::label('estado', 'Estado', ['class' => 'col-md-4 control-label']) !!}
							<div class="col-md-6">
								{!! Form::text('estado', old('estado'),['class' => 'form-control']	)!!}
									
								@if ($errors->has('estado'))
									<span class="help-block">
										<strong>{{ $errors->first('estado') }}</strong>
									</span>
								@endif
							</div>
						</div>
					
					<!-- Ciudad -->
						<div class="form-group{{ $errors->has('ciudad') ? ' has-error' : '' }}">
							{!! Form::label('ciudad', 'Ciudad', ['class' => 'col-md-4 control-label']) !!}
							<div class="col-md-6">
								{!! Form::text('ciudad', old('ciudad'),['class' => 'form-control']	)!!}
									
								@if ($errors->has('ciudad'))
									<span class="help-block">
										<strong>{{ $errors->first('ciudad') }}</strong>
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
								<input type="hidden" name="opcion" value="crear_cuenta"/>
							</div>
						</div>

					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
