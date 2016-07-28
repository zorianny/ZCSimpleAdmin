@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Inicio de Sesi&oacute;n</div>
				<div class="panel-body">

					{!! Form::open(['url' => '/login', 'role' => 'form', 'class' => 'form-horizontal']) !!}
					<!--form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}"-->
						<!--{{ csrf_field() }} -->

						<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
							{!! Form::label('email', 'Direcci&oacute;n de Correo', ['class' => 'col-md-4 control-label']) !!}

							<div class="col-md-6">
								{!! Form::email('email', old('email'),['class' => 'form-control']	)!!}
								
								@if ($errors->has('email'))
									<span class="help-block">
										<strong>{{ $errors->first('email') }}</strong>
									</span>
								@endif
							</div>
						</div>

						<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
							{!! Form::label('password', 'Contrase&ntilde;a', ['class' => 'col-md-4 control-label']) !!}

							<div class="col-md-6">
								{!! Form::password('password', ['class' => 'form-control']) !!}

								@if ($errors->has('password'))
									<span class="help-block">
										<strong>{{ $errors->first('password') }}</strong>
									</span>
								@endif
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="remember"> No cerrar sesi&oacute;n
									</label>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									<i class="fa fa-btn fa-sign-in"></i> Inicio de Sesi&oacute;n
								</button>

								<a class="btn btn-link" href="{{ url('/password/reset') }}">Olvid&oacute; su contrase&ntilde;a?</a>
							</div>
						</div>
					<!--/form-->
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
