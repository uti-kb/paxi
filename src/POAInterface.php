<?php

namespace Hakger\Paxi;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

/**
 * Description of POAInterface
 *
 * @author Hubert Kowalski
 */
class POAInterface implements LoggerAwareInterface
{
    // configuration

    /**
     *
     * @var string ip, port i katalog rpc do poa
     */
    private $rpc_url; // = 'http://192.168.128.91:8440/RPC2';

    /**
     * tu należy podać dane do logowania do api w formacie "user:pass"
     * lub false w przypadku braku wymagania logowania
     *
     * @var string|boolean
     */
    private $api_access = false;

    // implementation

    /**
     * @var Array Array klientów xmlRpc.
     */
    private $xml_client;

    /**
     * The logger instance.
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Sets a logger.
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __construct(
        $url = '',
        $api_access = false,
        LoggerInterface $logger = null
    ) {
        $this->api_access = $api_access;
        $this->rpc_url = '';
        $this->xml_client = array();
        if (!empty($url) && filter_var($url, FILTER_VALIDATE_URL) !== false) {
            $this->rpc_url = $url;
        }
        $this->logger = $logger;
    }

    public function setUrl($url)
    {
        if (!empty($url) && filter_var($url, FILTER_VALIDATE_URL) !== false) {
            $this->rpc_url = $url;
            $this->xml_client = array(); // reset clients array
        } else {
            throw new PAException("URL '$url' is invalid!");
        }
    }

    private function checkRestStatus(&$ret)
    {
        if ($ret['status'] == -1) {
            throw new POAXMLRPCException("POA ERROR:
            module[{$ret['module_id']}] type[{$ret['extype_id']}]\n
            MESSAGE: {$ret['error_message']}");
        }
    }

    /**
     * Odolaj sie do odpowiedniej metody klienta XMLRPC
     *
     * @param string $namespace przestrzen nazw funkcji POA
     * @return \Hakger\Paxi\XMLRPCClient
     */
    private function xmlClient($namespace = '')
    {
        if (empty($this->rpc_url)) {
            throw new PAException("URL '$this->rpc_url' is invalid!");
        }
        $ns = $namespace == '' ? 'pem' : "pem.$namespace";
        if (!array_key_exists($ns, $this->xml_client) ||
                (
                 ! $this->xml_client[$ns] instanceof \Hakger\Paxi\XMLRPCClient
                )
            ) {
            $this->xml_client[$ns] = new XMLRPCClient(
                $this->rpc_url,
                $ns,
                $this->api_access,
                $this->logger
            );
        }
        return $this->xml_client[$ns];
    }

    public function activateSubscription(array $params)
    {
        $ret = $this->xmlClient()->activateSubscription($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function addSubscription(array $params)
    {
        $ret = $this->xmlClient()->addSubscription($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function addDomain(array $params)
    {
        $ret = $this->xmlClient()->addDomain($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function bindServicesToDomain(array $params)
    {
        $ret = $this->xmlClient()->bindServicesToDomain($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function unbindServicesFromDomain(array $params)
    {
        $ret = $this->xmlClient()->unbindServicesFromDomain($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function addMailbox(array $params)
    {
        $ret = $this->xmlClient('cqmail')->addMailbox($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function addMailForwarding(array $params)
    {
        $ret = $this->xmlClient('cqmail')->addMailForwarding($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function editMailname(array $params)
    {
        $ret = $this->xmlClient('cqmail')->editMailname($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function editMailAddresses(array $params)
    {
        $ret = $this->xmlClient('cqmail')->editEmailAddresses($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function getSubscriptionWebspaces(array $params)
    {
        $ret = $this->xmlClient()->getSubscriptionWebspaces($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function getWebspacesList(array $params)
    {
        $ret = $this->xmlClient()->getWebspacesList($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function getAccountInfo(array $params)
    {
        $ret = $this->xmlClient()->getAccountInfo($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function assignRolesToMember(array $params)
    {
        $ret = $this->xmlClient()->assignRolesToMember($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function getUsers(array $params)
    {
        $ret = $this->xmlClient()->getUsers($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function getUserByLogin(array $params)
    {
        $ret = $this->xmlClient()->getUserByLogin($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function modifyUser(array $params)
    {
        $ret = $this->xmlClient()->modifyUser($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function getSubscription(array $params)
    {
        $ret = $this->xmlClient()->getSubscription($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function addDNSHosting(array $params)
    {
        $ret = $this->xmlClient()->addDNSHosting($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function addDNSRecord(array $params)
    {
        $ret = $this->xmlClient()->createDNSRecord($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function delDNSRecord(array $params)
    {
        $ret = $this->xmlClient()->deleteDNSRecord($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function registerUserInApplicationInstance(array $params)
    {
        $ret = $this->xmlClient('APS')
            ->registerUserInApplicationInstance($params);
        $this->checkRestStatus($ret);
        return $ret;
    }
}
