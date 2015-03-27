<?php

namespace Hakger\Paxi;

/**
 * Description of POAInterface
 *
 * @author Hubert Kowalski
 */
class POAInterface
{
    // configuration

    /**
     * tu nalezy podać ip, port i katalog rpc do poa
     *
     * @var string
     */
    private $rpc_url; // = 'http://192.168.128.91:8440/RPC2';

    /**
     * tu należy podać dane do logowania do api w formacie "user:pass"
     * lub false w przypadku braku wymagania logowania
     *
     * //private $api_access = "uti-api2:x6IH3ozJMVOp96"
     *
     * @var string|boolean
     */
    private $api_access = false;

    // implementation

    /**
     * @var Array Array klientów xmlRpc.
     */
    private $xml_client;

    public function __construct($url = '', $api_access = false)
    {
        $this->api_access = $api_access;
        if (!empty($url) && filter_var($url, FILTER_VALIDATE_URL) !== false) {
            $this->rpc_url = $url;
            $this->xml_client['pem'] = new XMLRPCClient(
                $this->rpc_url,
                'pem',
                $this->api_access
            );
            $this->xml_client['pem.cqmail'] = new XMLRPCClient(
                $this->rpc_url,
                'pem.cqmail',
                $this->api_access
            );
            $this->xml_client['pem.APS'] = new XMLRPCClient(
                $this->rpc_url,
                'pem.APS',
                $this->api_access
            );
        }
    }

    public function setUrl($url)
    {
        if (!empty($url) && filter_var($url, FILTER_VALIDATE_URL) !== false) {
            $this->xml_client['pem'] = new XMLRPCClient(
                $this->rpc_url,
                'pem',
                $this->api_access
            );
            $this->xml_client['pem.cqmail'] = new XMLRPCClient(
                $this->rpc_url,
                'pem.cqmail',
                $this->api_access
            );
            $this->xml_client['pem.APS'] = new XMLRPCClient(
                $this->rpc_url,
                'pem.APS',
                $this->api_access
            );
        } else {
            throw new PBAException("URL '$url' is invalid!");
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

    public function activateSubscription(array $params)
    {
        $ret = $this->xml_client['pem']->activateSubscription($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function addSubscription(array $params)
    {
        $ret = $this->xml_client['pem']->addSubscription($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function addDomain(array $params)
    {
        $ret = $this->xml_client['pem']->addDomain($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function bindServicesToDomain(array $params)
    {
        $ret = $this->xml_client['pem']->bindServicesToDomain($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function unbindServicesFromDomain(array $params)
    {
        $ret = $this->xml_client['pem']->unbindServicesFromDomain($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function addMailbox(array $params)
    {
        $ret = $this->xml_client['pem.cqmail']->addMailbox($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function addMailForwarding(array $params)
    {
        $ret = $this->xml_client['pem.cqmail']->addMailForwarding($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function editMailname(array $params)
    {
        $ret = $this->xml_client['pem.cqmail']->editMailname($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function editMailAddresses(array $params)
    {
        $ret = $this->xml_client['pem.cqmail']->editEmailAddresses($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function getSubscriptionWebspaces(array $params)
    {
        $ret = $this->xml_client['pem']->getSubscriptionWebspaces($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function getWebspacesList(array $params)
    {
        $ret = $this->xml_client['pem']->getWebspacesList($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function getAccountInfo(array $params)
    {
        $ret = $this->xml_client['pem']->getAccountInfo($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function assignRolesToMember(array $params)
    {
        $ret = $this->xml_client['pem']->assignRolesToMember($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function getUsers(array $params)
    {
        $ret = $this->xml_client['pem']->getUsers($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function getUserByLogin(array $params)
    {
        $ret = $this->xml_client['pem']->getUserByLogin($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function modifyUser(array $params)
    {
        $ret = $this->xml_client['pem']->modifyUser($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function getSubscription(array $params)
    {
        $ret = $this->xml_client['pem']->getSubscription($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function addDNSHosting(array $params)
    {
        $ret = $this->xml_client['pem']->addDNSHosting($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function addDNSRecord(array $params)
    {
        $ret = $this->xml_client['pem']->createDNSRecord($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function delDNSRecord(array $params)
    {
        $ret = $this->xml_client['pem']->deleteDNSRecord($params);
        $this->checkRestStatus($ret);
        return $ret;
    }

    public function registerUserInApplicationInstance(array $params)
    {
        $ret =
            $this->xml_client['pem.APS']
            ->registerUserInApplicationInstance($params);
        $this->checkRestStatus($ret);
        return $ret;
    }
}
