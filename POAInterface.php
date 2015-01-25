<?php
namespace hakger\paxi;
require_once dirname(__FILE__).'/XMLRPCClient.php';

class POAXMLRPCException extends Exception {}

/**
 * Description of POAInterface
 *
 * @author Hubert Kowalski
 */
class POAInterface {
	
	//configuration
	
	/**
	 * tu nalezy podać ip, port i katalog rpc do poa
	 * 
	 * @var string 
	 */
	private $RPC_URL = "http://192.168.128.91:8440/RPC2";
	
	/**
	 * tu należy podać dane do logowania do api w formacie "user:pass" lub false w przypadku braku wymagania logowania
	 *
	 * //private $api_access = "uti-api2:x6IH3ozJMVOp96"
	 * 
	 * @var string|boolean 
	 */
	private $api_access = false;
	
	//implementation
	
	/**
	 * klient xml do głównego namespace.
	 * 
	 * @var XMLRPCClient 
	 */
	private $xml_client;
	
	/**
	 * Klient xml do namespace qmail
	 * 
	 * @var XMLRPCClient
	 */
	private $xml_qmail;
	
	/**
	 *Klient xml do namespace APS
	 * @var type 
	 */
	private $xml_APS;
	
	
	public function __construct() {
		$this->xml_client = new XMLRPCClient($this->RPC_URL, 'pem', $this->api_access);
		$this->xml_qmail = new XMLRPCClient($this->RPC_URL, 'pem.cqmail', $this->api_access);
		$this->xml_APS = new XMLRPCClient($this->RPC_URL, 'pem.APS', $this->api_access);
		
	}
	
	private function checkRestStatus(&$ret){
		if($ret['status'] == -1){
			throw new POAXMLRPCException("POA ERROR: module[{$ret['module_id']}] type[{$ret['extype_id']}]\n MESSAGE: {$ret['error_message']}");
		}
	}
	
	public function ActivateSubscription(array $params){
		$ret = $this->xml_client->activateSubscription($params);
		$this->checkRestStatus($ret);
		return $ret;
	}
	
	public function addSubscription(array $params){
		$ret =  $this->xml_client->addSubscription($params);
		$this->checkRestStatus($ret);
		return $ret;
	}
	
	public function addDomain(array $params){
		$ret =  $this->xml_client->addDomain($params);
		$this->checkRestStatus($ret);
		return $ret;
	}
	
	public function bindServicesToDomain(array $params){
		$ret =  $this->xml_client->bindServicesToDomain($params);
		$this->checkRestStatus($ret);
		return $ret;
	}
	public function unbindServicesFromDomain(array $params){
		$ret =  $this->xml_client->unbindServicesFromDomain($params);
		$this->checkRestStatus($ret);
		return $ret;
	}
	
	public function addMailbox(array $params) {
		
		$ret =  $this->xml_qmail->addMailbox($params);
		$this->checkRestStatus($ret);
		return $ret;
	}
	
	public function addMailForwarding(array $params) {
		$ret =  $this->xml_qmail->addMailForwarding($params);
		$this->checkRestStatus($ret);
		return $ret;
	}
	
	public function editMailname(array $params) {
		$ret =  $this->xml_qmail->editMailname($params);
		$this->checkRestStatus($ret);
		return $ret;
	}
	
	public function editMailAddresses(array $params) {
		$ret =  $this->xml_qmail->editEmailAddresses($params);
		$this->checkRestStatus($ret);
		return $ret;
	}
	
	public function getSubscriptionWebspaces(array $params){
		$ret = $this->xml_client->getSubscriptionWebspaces($params);
		$this->checkRestStatus($ret);
		return $ret;
	}
	
	public function getWebspacesList(array $params){
		$ret = $this->xml_client->getWebspacesList($params);
		$this->checkRestStatus($ret);
		return $ret;
	}
	
	public function getAccountInfo(array $params){
		$ret = $this->xml_client->getAccountInfo($params);
		$this->checkRestStatus($ret);
		return $ret;
	}
	
	public function assignRolesToMember(array $params){
		$ret = $this->xml_client->assignRolesToMember($params);
		$this->checkRestStatus($ret);
		return $ret;
	}
	
	public function getUsers(array $params){
		$ret = $this->xml_client->getUsers($params);
		$this->checkRestStatus($ret);
		return $ret;
	}
	
	public function getUserByLogin(array $params){
		$ret = $this->xml_client->getUserByLogin($params);
		$this->checkRestStatus($ret);
		return $ret;
	}
	
	public function modifyUser(array $params){
		$ret = $this->xml_client->modifyUser($params);
		$this->checkRestStatus($ret);
		return $ret;
	}
	
	public function getSubscription(array $params){
		$ret = $this->xml_client->getSubscription($params);
		$this->checkRestStatus($ret);
		return $ret;
	}
	
	public function addDNSHosting(array $params){
		$ret = $this->xml_client->addDNSHosting($params);
		$this->checkRestStatus($ret);
		return $ret;
	}
	
		public function addDNSRecord(array $params){
		$ret = $this->xml_client->createDNSRecord($params);
		$this->checkRestStatus($ret);
		return $ret;
	}

		public function delDNSRecord(array $params){
		$ret = $this->xml_client->deleteDNSRecord($params);
		$this->checkRestStatus($ret);
		return $ret;
	}

	public function registerUserInApplicationInstance(array $params){
		$ret = $this->xml_APS->registerUserInApplicationInstance($params);
		$this->checkRestStatus($ret);
		return $ret;
	}
	
}
