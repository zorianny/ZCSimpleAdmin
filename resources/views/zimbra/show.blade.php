@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Creaci&oacute;n de Cuenta de Correo</div>

					<div class="panel-body">
						@if ($codError > 0)
						<div class="alert alert-danger" role="alert">
							{!! $message !!}
						</div>
						@else
						<div class="alert alert-success" role="alert">
							{!! $message !!}
						</div>
						@endif
						@if ($codError == 0)
							@if ($nombre)
								<b>Nombre:</b> {!! $nombre !!} <br/>
							@endif
							@if ($apellido)
								<b>Apellido:</b> {!! $apellido !!}  <br/>
							@endif
							@if ($cuenta)
								<b>Direcci&oacute;n de Correo:</b> {!! $cuenta!!}  <br/>
							@endif
							@if ($cedula)
								<b>C&eacute;dula de Identidad:</b> {!! $cedula!!}  <br/>
							@endif
							@if ($direccion)
								<b>Empresa:</b> {!! $direccion !!}   <br/>
							@endif
							@if ($profesion)
								<b>T&iacute;tulo Profesional:</b> {!! $profesion !!}  <br/>
							@endif
							@if ($pais)
								<b>Pa&iacute;s:</b> {!! $pais !!}  <br/>
							@endif
							@if ($estado)
								<b>Estado:</b> {!! $estado !!}  <br/>
							@endif
							@if ($ciudad)
								<b>Ciudad:</b> {!! $ciudad !!}	 <br/>
							@endif
						@endif
					</div>
				
				</div>
			</div>
	</div>
</div>
@endsection
