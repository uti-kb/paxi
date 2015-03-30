<?php

namespace Hakger\Paxi;

/**
 * PBAInterface - biblioteka do xmlrpc
 *
 * @author Hubert Kowalski
 */
class PBAInterface
{
    // configuration

    /**
     * @var string ip, port i katalog rpc do pba
     */
    private $rpc_url; // = 'http://192.168.128.78:5224/RPC2';

    /**
     * @var XMLRPCClient klient xml do głównego namespace.
     */
    private $xml_client;

    public function __construct($url = '')
    {
        if (!empty($url) && filter_var($url, FILTER_VALIDATE_URL) !== false) {
            $this->rpc_url = $url;
            $this->xml_client = new XMLRPCClient($this->rpc_url, '');
        }
    }

    public function setUrl($url)
    {
        if (!empty($url) && filter_var($url, FILTER_VALIDATE_URL) !== false) {
            $this->xml_client = new XMLRPCClient($this->rpc_url, '');
        } else {
            throw new PBAException("URL '$url' is invalid!");
        }
    }

    private function checkCnt(&$params, $min_cnt)
    {
        if (count($params) < $min_cnt) {
            throw new PBAException("Too little params count," .
            " needs at least $min_cnt");
        }
    }

    public function addAccount(array $params = array())
    {
        $min_cnt = 62;
        $this->checkCnt($params, $min_cnt);
        $request = array(
            'Server' => 'BM',
            'Method' => 'AccountAdd_API',
            'Lang' => 'pl',
            'Params' => $params,
        );
        return $this->xml_client->Execute($request);
    }

    public function addAccountMember(array $params = array())
    {
        $min_cnt = 23;
        $this->checkCnt($params, $min_cnt);
        $request = array(
            'Server' => 'BM',
            'Method' => 'UserAdd_API',
            'Lang' => 'pl',
            'Params' => $params,
        );
        return $this->xml_client->Execute($request);
    }

    public function delAccountMember(array $params = array())
    {
        $min_cnt = 1;
        $this->checkCnt($params, $min_cnt);
        $request = array(
            'Server' => 'BM',
            'Method' => 'UserRemove_API',
            'Lang' => 'pl',
            'Params' => $params,
        );
        return $this->xml_client->Execute($request);
    }

    public function updateAccount(array $params = array())
    {
        $min_cnt = 46;
        $this->checkCnt($params, $min_cnt);
        $request = array(
            'Server' => 'BM',
            'Method' => 'AccountUpdate_API',
            'Lang' => 'pl',
            'Params' => $params,
        );
        return $this->xml_client->Execute($request);
    }

    public function updateAccountMember(array $params = array())
    {
        $min_cnt = 20;
        $this->checkCnt($params, $min_cnt);
        $request = array(
            'Server' => 'BM',
            'Method' => 'UserUpdate_API',
            'Lang' => 'pl',
            'Params' => $params,
        );
        return $this->xml_client->Execute($request);
    }

    public function addPEMSubscription(array $params = array())
    {
        $min_cnt = 19;
        $this->checkCnt($params, $min_cnt);
        $request = array(
            'Server' => 'PEMGATE',
            'Method' => 'AddPEMSubscription_API',
            'Lang' => 'pl',
            'Params' => $params,
        );
        return $this->xml_client->Execute($request);
    }

    public function addDomainSubscription(array $params = array())
    {
        $min_cnt = 30;
        $this->checkCnt($params, $min_cnt);
        $request = array(
            'Server' => 'DOMAINGATE',
            'Method' => 'DomainSubscrAdd_API',
            'Lang' => 'pl',
            'Params' => $params,
        );
        return $this->xml_client->Execute($request);
    }

    public function addPEMDomainForSubscription(array $params = array())
    {
        $min_cnt = 2;
        $this->checkCnt($params, $min_cnt);
        $request = array(
            'Server' => 'PEMGATE',
            'Method' => 'AddPEMDomainForSubscription_API',
            'Lang' => 'pl',
            'Params' => $params,
        );
        return $this->xml_client->Execute($request);
    }

    public function updateObjAttrList(array $params = array())
    {
        $min_cnt = 4;
        $this->checkCnt($params, $min_cnt);
        $request = array(
            'Server' => 'BM',
            'Method' => 'UpdateObjAttrList_API',
            'Lang' => 'pl',
            'Params' => $params,
        );
        return $this->xml_client->Execute($request);
    }

    public function addKAAddon(array $params = array())
    {
        $min_cnt = 2;
        $this->checkCnt($params, $min_cnt);
        $request = array(
            'Server' => 'KAGATE',
            'Method' => 'KA_AddonAdd',
            'Lang' => 'pl',
            'Params' => $params,
        );
        return $this->xml_client->Execute($request);
    }

    public function removeKAAddon(array $params = array())
    {
        $min_cnt = 2;
        $this->checkCnt($params, $min_cnt);
        $request = array(
            'Server' => 'KAGATE',
            'Method' => 'KA_AddonMoveToArc',
            'Lang' => 'pl',
            'Params' => $params,
        );
        return $this->xml_client->Execute($request);
    }

    public function addKAFeatureGroup(array $params = array())
    {
        $min_cnt = 4;
        $this->checkCnt($params, $min_cnt);
        $request = array(
            'Server' => 'KAGATE',
            'Method' => 'KA_FeatureGroupAdd',
            'Lang' => 'pl',
            'Params' => $params,
        );
        return $this->xml_client->Execute($request);
    }

    public function removeKAFeatureGroup(array $params = array())
    {
        $min_cnt = 1;
        $this->checkCnt($params, $min_cnt);
        $request = array(
            'Server' => 'KAGATE',
            'Method' => 'KA_FeatureGroupMoveToArc',
            'Lang' => 'pl',
            'Params' => $params,
        );
        return $this->xml_client->Execute($request);
    }

    public function addKAUpgrade(array $params = array())
    {
        $min_cnt = 6;
        $this->checkCnt($params, $min_cnt);
        $request = array(
            'Server' => 'KAGATE',
            'Method' => 'KA_UpgradeAdd',
            'Lang' => 'pl',
            'Params' => $params,
        );
        return $this->xml_client->Execute($request);
    }

    public function removeKAUpgrade(array $params = array())
    {
        $min_cnt = 2;
        $this->checkCnt($params, $min_cnt);
        $request = array(
            'Server' => 'KAGATE',
            'Method' => 'KA_UpgradeMoveToArc',
            'Lang' => 'pl',
            'Params' => $params,
        );
        return $this->xml_client->Execute($request);
    }
}
