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
   * Show view Modificacion de Clave de Correo
   *
   * @return \Illuminate\Http\Response
   */
	public function modify()
  {
  	return view('zimbra/modify');
	}

    /**
     **
     * Funcion que permite la creacion de correos en Zimbra
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	public function store(Request $request)
	{
			$this->isValid($request);
		
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
						'direccion' => $request->input('direccion'),
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
				'direccion' => $request->input('direccion'),
				'profesion' => $request->input('profesion'),
				'pais' => $request->input('pais'),
				'estado' => $request->input('estado'),
				'ciudad' => $request->input('ciudad'),
				'opcion' => $request->input('opcion'),
							 );
			//crear cuenta de correo en Zimbra
			switch($request->input('opcion')){
				case 'crear_cuenta':
					$zimbra->createAccountZimbra($datos);		
					break;
				case 'cambiar_clave':
					$zimbra->modifyAccountZimbra($datos);
					break;
				default:
					break;
			}
			

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
				'direccion' => $request->input('direccion'),
				'profesion' => $request->input('profesion'),
				'pais' => $request->input('pais'),
				'estado' => $request->input('estado'),
				'ciudad' => $request->input('ciudad')
			]); 
	}
	
	/**
	 *
	 *
	 */
	public function isValid($request)
	{
		switch($request->input('opcion'))
		{
			case 'crear_cuenta':
				$validator = $this->validate($request, [
					'nombre' => 'required|alpha_num_spaces',
					'apellido' => 'required|alpha_num_spaces',
					'cedula' => 'required|numeric',
					'cuenta' => 'required|cuenta_punto',
					'clave' => 'required|min:8|confirmed',
					'direccion' => 'required|alpha_num_special_char',
					'profesion' => 'required|alpha_spaces',
					'pais' => 'required|alpha_spaces',
					'estado' => 'required|alpha_spaces',
					'ciudad' => 'required|alpha_spaces'
				]);
				break;
			case 'cambiar_clave':
				$validator = $this->validate($request, [
					'cuenta' => 'required|alpha_num',
					'clave' => 'required|min:8|confirmed',
				]);
				break;
			default:
				$validator = $this->validate($request, [
					'nombre' => 'required|alpha_num_spaces',
					'apellido' => 'required|alpha_num_spaces',
					'cedula' => 'required|numeric',
					'cuenta' => 'required|cuenta_punto',
					'clave' => 'required|min:8|confirmed',
					'direccion' => 'required|alpha_num_special_char',
					'profesion' => 'required|alpha_spaces',
					'pais' => 'required|alpha_spaces',
					'estado' => 'required|alpha_spaces',
					'ciudad' => 'required|alpha_spaces'
				]);
				break;
		}
		
	}
	
}
