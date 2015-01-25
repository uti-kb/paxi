<?php

class XmlRpcClientException extends Exception {
	public function __construct($message, $code, $previous) {
		if(is_array($message)){
			parent::__construct($message['faultString'], $code, $previous);
		} else {
			parent::__construct($message, $code, $previous);
		}
	}
}

/**
 * XMLRPCClient
 *
 * @author Hubert Kowalski
 */
class XMLRPCClient {

	protected $_url;
	protected $_namespace;
	protected $_clients;
	protected $_authorisation = false;

	public function __construct($url, $namespace = '', $authorisation = false) {
		$this->_url = (string)$url;
		$this->_namespace = (string)$namespace;
		$this->_clients = array();
		if($authorisation) {
			$this->_authorisation = base64_encode($authorisation);
		}
	}

	public function __get($namespace) {
		if (!key_exists($namespace, $this->_clients))
			$this->_clients[$namespace] = new XmlRpcClient($this->_url, strlen($this->_namespace) > 0 ? "$this->_namespace.$namespace" : $namespace);

		return $this->_clients[$namespace];
	}

	public function __call($method, array $parameters = array()) {
		$logname = (strlen($this->_namespace) > 0 ? "$this->_namespace.$method" : $method);
		if(!empty($parameters[0]['Server'])){
			$logname .= "({$parameters[0]['Server']}-{$parameters[0]['Method']})";
		}
		error_log("XMLRPC call: $logname");
		$request = xmlrpc_encode_request(
			strlen($this->_namespace) > 0 ? "$this->_namespace.$method" : $method, 
			$parameters, 
			array(
				'encoding'=>'UTF-8',
				'escaping'=> 'markup',
				)
			);
		$request = preg_replace('%<value>\s*<string>(.*?)</string>\s*</value>%im', '<value>\1</value>', $request);
		$request = preg_replace('%<value>\s*<int>(.*?)</int>\s*</value>%im', '<value><i4>\1</i4></value>', $request);
		$request = preg_replace('%<value>\s*<string/>\s*</value>%im', '<value></value>', $request);
		$request = html_entity_decode($request);
		
		file_put_contents(dirname(__FILE__).'/lastrequest.xml', $request);
		file_put_contents(dirname(__FILE__)."/logs/{$logname}_call.xml", $request);
		$hdr_end = '';
		if($this->_authorisation){
			$hdr_end = "\r\nAuthorization: Basic {$this->_authorisation}\r\n";
		}
		$context = stream_context_create(
			array(
				'http' => array(
					'method' => "POST",
					'header' => "Content-Type: text/xml",
					'content' => $request
				)
			)
		);

		$file = file_get_contents($this->_url, false, $context);
		file_put_contents(dirname(__FILE__)."/logs/{$logname}_response.xml", $file);
		$response = xmlrpc_decode($file);

		if (!$response)
			throw new XmlRpcClientException(array('faultString' => 'Invalid response from ' . $this->_url));

		if (xmlrpc_is_fault($response))
			throw new XmlRpcClientException($response);

		return $response;
	}

}
