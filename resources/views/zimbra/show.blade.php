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
							<b>Nombre:</b> {!! $nombre !!} <br/>
							<b>Apellido:</b> {!! $apellido !!}  <br/>
							<b>Direcci&oacute;n de Correo:</b> {!! $cuenta!!}  <br/>
							<b>C&eacute;dula de Identidad:</b> {!! $cedula!!}  <br/>
							<b>Empresa:</b> {!! $empresa !!}   <br/>
							<b>T&iacute;tulo Profesional:</b> {!! $profesion !!}  <br/>
							<b>Pa&iacute;s:</b> {!! $pais !!}  <br/>
							<b>Estado:</b> {!! $estado !!}  <br/>
							<b>Ciudad:</b> {!! $ciudad !!}	 <br/>	
					</div>
				
				</div>
			</div>
	</div>
</div>
@endsection
