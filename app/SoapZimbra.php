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
								'<a n="company">'.$datos["empresa"].'</a>'.
								//'<company>Ministerio Publico</company>'.
								//'<a n="jobTitle">Ministerio Publico</a>'.
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
	 *
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
	 *
	 *
	 */
	public function getMsgError()
	{
		return $this->message;
	}
	
	/**
	 *
	 *
	 */
	public function getCodeError()
	{
		return $this->error;
	}

   /**
   *
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
	
	public function clearResponse()
	{
		$clean_xml = str_ireplace(['SOAP-ENV:', 'SOAP:'], '', $this->response);
		$xml = simplexml_load_string($clean_xml);
		if(isset($xml->Body->Fault->Reason->Text))
			$this->faultErrorReason = $xml->Body->Fault->Reason->Text;
		if(isset($xml->Body->Fault->Detail->Error->Code))
			$this->faultErrorCode = $xml->Body->Fault->Detail->Error->Code;
		return;
	}
	
	public function getCodeErrorZimbra()
	{
		return $this->faultErrorCode;
	}
	
	public function getDetalleErrorZimbra()
	{
		return $this->faultErrorReason;
	}
	
	public function getErrorZimbra()
	{
		$this->error = 99;
		switch($this->faultErrorCode)
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
			default:
				$this->message = "Error al crear la cuenta. Consulte con su Administrador de Sistemas. <br/>C&oacute;digo: ".$this->faultErrorCode." <br/>Motivo: ".$this->faultErrorReason;
				break;
		}
		return;
	}
	
	public function translateResponseXML()
	{
		$this->clearResponse();
		$this->getErrorZimbra();
	}		
	
}
?>
