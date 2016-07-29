<?php

namespace App\Http\Controllers\Zimbra;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\SoapZimbra;

class SoapZimbraAdminController extends Controller
{
   /**
    * Create a new controller instance.
    *
    * @return void
    */
	public function __construct()
  {
		$this->middleware('auth');
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
  	return view('home');
  }

  /**
   * Show view Creacion de Cuentas de Correo
   *
   * @return \Illuminate\Http\Response
   */
	public function create()
  {
  	return view('zimbra/create');
	}

    /**
     **
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	public function store(Request $request)
	{
		//$validator = $this->isValid($request);
		$validator = $this->validate($request, [
        'nombre' => 'required|alpha_num_spaces',
        'apellido' => 'required|alpha_num_spaces',
				'cedula' => 'required|numeric',
				'cuenta' => 'required|alpha_num',
				'clave' => 'required|min:8|confirmed',
				'empresa' => 'required|alpha_num_spaces',
				'profesion' => 'required|alpha_spaces',
				'pais' => 'required|alpha_spaces',
				'estado' => 'required|alpha_spaces',
				'ciudad' => 'required|alpha_spaces'
    ]);
		
			//obtener los valores predeterminados para establecer la conexion a Zimbra
			$server  = config('zimbrasoap.servidor');
			$port    = config('zimbrasoap.puerto'); 
			$dominio = config('zimbrasoap.dominio'); 
			$arroba  = config('zimbrasoap.arroba');
			$admin   = config('zimbrasoap.usuario');
			$clave   = config('zimbrasoap.clave'); 
			$zimbra  = new SoapZimbra();

			//realizar la autenticacion al servidor Zimbra
			$zimbra->authZimbra($server, $port, $admin.$arroba.$dominio, $clave);

			//capturar errores si existen al establecer la conexion
			$msg    = $zimbra->getMsgError();
			$codMsg = $zimbra->getCodeError();

			if($codMsg != 0) //si tiene errores de conexion muestra mensaje de error y se detiene el proceso
			{
				return view('zimbra/show', [
						'codError' => $codMsg,  
						'message' => $msg, 
						'nombre' => ucwords(strtolower($request->input('nombre'))), 
						'apellido' => ucwords(strtolower($request->input('apellido'))),
						'cedula' => $request->input('cedula'),
						'cuenta' => $request->input('cuenta').$arroba.$dominio,
						'empresa' => $request->input('empresa'),
						'profesion' => $request->input('profesion'),
						'pais' => $request->input('pais'),
						'estado' => $request->input('estado'),
						'ciudad' => $request->input('ciudad')
						]); 
			}

			//si la conexion fue establecida con exito continua la creacion de la cuenta
			$datos = array(
				'cuenta' => $request->input('cuenta').$arroba.$dominio,
				'clave'  => $request->input('clave'),
				'nombre' => ucwords(strtolower($request->input('nombre'))),
				'apellido' => ucwords(strtolower($request->input('apellido'))),
				'cedula' => $request->input('cedula'),
				'empresa' => $request->input('empresa'),
				'profesion' => $request->input('profesion'),
				'pais' => $request->input('pais'),
				'estado' => $request->input('estado'),
				'ciudad' => $request->input('ciudad')
							 );
			//crear cuenta de correo en Zimbra
			$zimbra->createAccountZimbra($datos);

			//capturar errores si existen al crear la cuenta
			$msg    = $zimbra->getMsgError();
			$codMsg = $zimbra->getCodeError();

			//$zimbra->showDebug();die();

			return view('zimbra/show', [
				'codError' => $codMsg,  
				'message' => $msg, 
				'nombre' => $request->input('nombre'), 
				'apellido' => $request->input('apellido'),
				'cedula' => $request->input('cedula'),
				'cuenta' => $datos['cuenta'],
				'empresa' => $request->input('empresa'),
				'profesion' => $request->input('profesion'),
				'pais' => $request->input('pais'),
				'estado' => $request->input('estado'),
				'ciudad' => $request->input('ciudad')
			]); 
	}
	
}
