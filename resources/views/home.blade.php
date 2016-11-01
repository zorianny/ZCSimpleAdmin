@extends('layouts.app')

@section('content')

	@if (!Auth::guest())
	<div class="container">
		<div class="row">
			<div class="col-md-3 col-sd-12">
				<a href="{!! route('correoZimbra.create') !!}"><img src="{!! url('img/opcion1.png') !!}" alt="Creaci&oacute;n de Cuenta" title="Creaci&oacute;n de Cuenta" class="img-circle"></a>
			</div>
	  	<div class="col-md-3 col-sd-12">
	  		<a href="{!! route('correoZimbra.modify') !!}"><img src="{!! url('img/opcion2.png') !!}" alt="Cambio de Clave" title="Cambio de Clave" class="img-circle"></a>
	  	</div>
	  	<!--div class="col-md-3 col-sd-12">
	  		<a href="#"><img src="{!! url('img/icono.png') !!}" alt="Sin opci&oacute;n" title="Sin opci&oacute;n" class="img-circle"></a>
	  	</div>
	  	<div class="col-md-3 col-sd-12">
	  		<a href="#"><img src="{!! url('img/icono.png') !!}" alt="Sin opci&oacute;n" title="Sin opci&oacute;n" class="img-circle"></a>
	  	</div-->
		</div>
	</div>
	@endif
	
@endsection