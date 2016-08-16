<?php

namespace App;
use Illuminate\Http\Request;

class SoapZimbra
{
	protected $authToken='';
  protected $sessionId='';
  protected $error=0;
	protected $message='';
  protected $response='';
  protected $request='';
  protected $SOAPhandle;
	protected $faultErrorReason='';
	protected $faultErrorCode='0';
	protected $responseBody='';

  /**
	 * Description: Funcion que permite la autenticacion en el Servidor Zimbra
	 * Parameters:
	 * 			@server: Direccion IP del Servidor Zimbra
	 * 			@port: Puerto del servicio
	 * 			@username: Nombre de usuario administrador de Zimbra
	 * 			@password: Clave del usuario administrador de Zimbra
	 * Return: void
	 */
	public function authZimbra($server, $port, $username, $password) 
	{
  	$url = "https://" . $server . ":".$port."/service/admin/soap/";
		$handle = curl_init();
		curl_setopt($handle, CURLOPT_URL, $url);
		curl_setopt($handle, CURLOPT_POST, TRUE);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, FALSE);

		$SOAPrequest  = 
        '<?xml version="1.0" encoding="ISO-8859-1"?>'. 
         '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope">'.
         '<soap:Header>'.
            '<context xmlns="urn:zimbra"/>'.
         '</soap:Header>'.
         '<soap:Body>'.
            '<AuthRequest xmlns="urn:zimbraAdmin">'.
               '<name>' . $username . '</name>'.
               '<password>' . $password . '</password>'.
            '</AuthRequest>'.
         '</soap:Body>'.
         '</soap:Envelope>';
 
		curl_setopt($handle, CURLOPT_POSTFIELDS, $SOAPrequest);
		$SOAPresponse = curl_exec($handle);
 
		if (!$SOAPresponse) {
			$this->error = curl_errno($handle);
			$this->setMsgError($this->error);				
        //curl_error($handle));
		}
 
		$authToken = strstr($SOAPresponse, "<authToken");
		$authToken = strstr($authToken, ">");
		$authToken = substr($authToken, 1, strpos($authToken, "<") - 1);
		$sessionID = strstr($SOAPresponse, "<sessionId");
		$sessionID = strstr($sessionID, ">");
		$sessionID = substr($sessionID, 1, strpos($sessionID, "<") - 1);

		$this->authToken = $authToken;
		$this->sessionID= $sessionID;
		$this->response = $SOAPresponse;
		$this->request = $SOAPrequest;
		$this->SOAPhandle = $handle;
     
		return;
	}

	/**
	 * Funcion que permite Crear Cuentas de Correo en Zimbra
	 *
	 *
	 */
	public function createAccountZimbra($datos) {
		$SOAPrequest = //$email, $password, $name, $surname
         '<?xml version="1.0" encoding="ISO-8859-1"?>'.
          '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope">'.
          '<soap:Header>'.
             '<context xmlns="urn:zimbra">'.
                '<authToken>' . $this->authToken . '</authToken>'.
                '<sessionId id="' . $this->sessionId . '">' . $this->sessionId . '</sessionId>'.
             '</context>'.
          '</soap:Header>'.
          '<soap:Body>'.
             '<CreateAccountRequest xmlns="urn:zimbraAdmin">'.
                '<name>' . $datos["cuenta"] . '</name>'.
                '<password>' . $datos["clave"] . '</password>'.			
                '<a n="givenName">'. $datos["nombre"] . '</a>'.
                '<a n="sn">'. $datos["apellido"] .'</a>'.
								'<a n="zimbraPasswordMustChange">TRUE</a>'.
								'<a n="description">'.$datos["cedula"].'</a>'.
								'<a n="co">'.$datos["pais"].'</a>'.
								'<a n="st">'.$datos["estado"].'</a>'.
								'<a n="l">'.utf8_encode($datos["ciudad"]).'</a>'.
								'<a n="title">'.$datos["profesion"].'</a>'.
								'<a n="company">'.$datos["direccion"].'</a>'.
             '</CreateAccountRequest>'.
          '</soap:Body>'.
          '</soap:Envelope>';

		$handle = $this->SOAPhandle;
		curl_setopt($handle, CURLOPT_POSTFIELDS, $SOAPrequest);
		$SOAPresponse = curl_exec($handle);
 
		if (!$SOAPresponse) {
			$this->error = curl_errno($handle);
			$this->setMsgError($this->error);				
		}

		$this->response = $SOAPresponse;
		$this->request = $SOAPrequest;
		$this->SOAPhandle = $handle;
		
		$this->translateResponseXML();
     
		return;

	}
	
	/**
	 * Funcion que asigna el mensaje de error que corresponde
	 *
	 */
	public function setMsgError($error)
	{
		switch($error)
		{
			case 0:
				$this->message = "Conexi&oacute;n establecida con el Servidor Zimbra";
				return;
			case 7: 
				$this->message = "Error al establecer la conexi&oacute;n con el Servidor Zimbra";
				return;	
			default:
				$this->message = "Error con el Servicio Web SOAP de Zimbra";
				return;	
		}
	}
	
	/**
	 * Funcion que devuelve el mensaje de error
	 *
	 */
	public function getMsgError()
	{
		return $this->message;
	}
	
	/**
	 * Funcion que obtiene el codigo del error
	 *
	 */
	public function getCodeError()
	{
		return $this->error;
	}

   /**
   * Funcion que despliega los valores de las variables request y response del web service
   * 
   */
	function showDebug() {
		echo "<br><br>DEBUG<br><br>";      
		echo '<h2>Request</h2><pre>'  .str_replace("\\n", "<bR>", htmlspecialchars( $this->request, ENT_QUOTES)). '</pre>';
    echo '<h2>Response</h2><pre>' . str_replace("\\n", "<bR>", htmlspecialchars( $this->response, ENT_QUOTES)). '</pre>';
		$clean_xml = str_ireplace(['SOAP-ENV:', 'SOAP:'], '', $this->response);
		$xml = simplexml_load_string($clean_xml);
		echo '<h2>Response</h2><pre>';
		print_r($xml);
		echo '</pre>';
		
		echo '<h2>authToken</h2><pre>' . $this->authToken. '</pre>';
		echo '<h2>sessionID</h2><pre>' . $this->sessionId. '</pre>';
		
		echo 'Nro Error: '.$this->error. ' '.$this->message;
		echo '<br>Nro Error: '.$this->faultErrorCode. ' '.$this->faultErrorReason;
	}
	
	/**
	 * Funcion que captura si la respuesta del web service es un error 
	 *
	 */
	public function clearResponse()
	{
		$clean_xml = str_ireplace(['SOAP-ENV:', 'SOAP:'], '', $this->response);
		$xml = simplexml_load_string($clean_xml);
		
		$this->responseBody = $xml->Body;
		
		if(isset($xml->Body->Fault->Reason->Text))
			$this->faultErrorReason = $xml->Body->Fault->Reason->Text;
		if(isset($xml->Body->Fault->Detail->Error->Code))
			$this->faultErrorCode = $xml->Body->Fault->Detail->Error->Code;
		return;
	}
	
	/**
	 * Funcion que obtiene el codigo de error de Zimbra
	 *
	 */
	public function getCodeErrorZimbra()
	{
		return $this->faultErrorCode;
	}
	
	/**
	 * Funcion que obtiene el detalle del error de Zimbra
	 *
	 */
	public function getDetalleErrorZimbra()
	{
		return $this->faultErrorReason;
	}
	
	/**
	 * Funcion que descifra el error del web service de zimbra
	 *
	 */
	public function getErrorZimbra()
	{
		$this->error = 99;
		switch($this->getCodeErrorZimbra())
		{
			case "0":
				$this->message = "Datos almacenados correctamente.";
				$this->error = 0;
				break;
			case "account.ACCOUNT_EXISTS":
				$this->message = "La cuenta de correo ya existe.";
				break;
			case "account.INVALID_PASSWORD":
				$this->message = "La contrase&ntilde;a es inv&aacute;lida.";
				break;
			case "account.NO_SUCH_ACCOUNT":
				$this->message = "La cuenta de correo no existe.";
				break;
			default:
				$this->message = "Error de Sistema. Consulte con su Administrador de Sistemas."; //CORREGIR
				//<br/>C&oacute;digo: ".$this->faultErrorCode." <br/>Motivo: ".$this->faultErrorReason;
				break;
		}
		return;
	}
	
	/**
	 * Funcion que traduce el XML de respuesta del Web Service SOAP de Zimbra
	 *
	 */
	public function translateResponseXML()
	{
		$this->clearResponse();
		$this->getErrorZimbra();
	}
	
	/**
	 * Description: Funcion que permite modificar una cuenta de correo Zimbra
	 *
	 */
	public function modifyAccountZimbra($datos)
	{
		$cuenta = $datos['cuenta'];
		$this->getAccountZimbra($cuenta);
		
		//capturar error si existe
		$error = $this->getCodeError();

		//si no hay error entonces continuar
		if($error == 0)
		{
			$prevId = substr($this->response, strpos($this->response,'<a n="zimbraId">'),strlen($this->response));
			$idZimbra = substr($prevId,strlen('<a n="zimbraId">'),strpos($prevId,'</a>') - strlen('<a n="zimbraId">'));
			
			switch ($datos['opcion'])
			{
				case 'cambiar_clave':
					$SOAPrequest = 
					 '<?xml version="1.0" encoding="ISO-8859-1"?>'.
						'<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope">'.
						'<soap:Header>'.
							 '<context xmlns="urn:zimbra">'.
									'<authToken>' . $this->authToken . '</authToken>'.
									'<sessionId id="' . $this->sessionId . '">' . $this->sessionId . '</sessionId>'.
							 '</context>'.
						'</soap:Header>'.
						'<soap:Body>'.
							 '<ModifyAccountRequest xmlns="urn:zimbraAdmin" id="'.$idZimbra.'">'.
									'<password>' . $datos["clave"] . '</password>'.
									'<a n="zimbraPasswordMustChange">TRUE</a>'.
							 '</ModifyAccountRequest>'.
						'</soap:Body>'.
						'</soap:Envelope>';
					break;
				defaut:
					break;		
			}
	
			$handle = $this->SOAPhandle;
			curl_setopt($handle, CURLOPT_POSTFIELDS, $SOAPrequest);
			$SOAPresponse = curl_exec($handle);
	 
			if (!$SOAPresponse) {
				$this->error = curl_errno($handle);
				$this->setMsgError($this->error);				
			}
	
			$this->response = $SOAPresponse;
			$this->request = $SOAPrequest;
			$this->SOAPhandle = $handle;
			
			$this->translateResponseXML();
		}
     
		return;		
	}
	
	
	public function getAccountZimbra($cuenta) 
	{
		$SOAPrequest = 
      '<?xml version="1.0" encoding="ISO-8859-1"?>'.
        '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope">'.
				  '<soap:Header>'.
            '<context xmlns="urn:zimbra">'.
              '<authToken>' . $this->authToken . '</authToken>'.
              '<sessionId id="' . $this->sessionId . '">' . $this->sessionId . '</sessionId>'.
            '</context>'.
					'</soap:Header>'.
					'<soap:Body>'.
					  '<GetAccountRequest xmlns="urn:zimbraAdmin">'.
					    '<account by="name">' . $cuenta . '</account>'.
					  '</GetAccountRequest>'.
					'</soap:Body>'.
				'</soap:Envelope>';
 
		$handle = $this->SOAPhandle;
		curl_setopt($handle, CURLOPT_POSTFIELDS, $SOAPrequest);
		$SOAPresponse = curl_exec($handle);
 
		if (!$SOAPresponse) {
			$this->error = curl_errno($handle);
			$this->setMsgError($this->error);				
		}

		$this->response = $SOAPresponse;
		$this->request = $SOAPrequest;
		$this->SOAPhandle = $handle;
		
		$this->translateResponseXML();
     
		return;
	}

}
?>
