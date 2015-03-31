<?php
namespace Hakger\Paxi;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

/**
 * XMLRPCClient
 *
 * @author Hubert Kowalski
 */
class XMLRPCClient implements LoggerAwareInterface
{

    protected $url;
    protected $namespace;
    protected $clients;
    protected $authorisation = false;

    /**
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct(
        $url,
        $namespace = '',
        $authorisation = false,
        LoggerInterface $logger = null
    ) {
        $this->url = (string)$url;
        $this->namespace = (string)$namespace;
        $this->clients = array();
        if ($authorisation) {
            $this->authorisation = base64_encode($authorisation);
        }
        $this->logger = $logger;
    }

    /**
        * Sets a logger.
        *
        * @param LoggerInterface $logger
    */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __get($namespace)
    {
        if (!key_exists($namespace, $this->clients)) {
            $this->clients[$namespace] = new XmlRpcClient(
                $this->url,
                strlen(
                    $this->namespace
                ) > 0 ? "$this->namespace.$namespace" : $namespace
            );
        }

        return $this->clients[$namespace];
    }

    public function __call($method, array $parameters = array())
    {
        $logname = (strlen(
            $this->namespace
        ) > 0 ? "$this->namespace.$method" : $method);
        if (!empty($parameters[0]['Server'])) {
            $logname .= "({$parameters[0]['Server']}
            -{$parameters[0]['Method']})";
        }

        if ($this->logger) {
            $this->logger->info("XMLRPC call: $logname");
        }
        $request = xmlrpc_encode_request(
            strlen($this->namespace) > 0 ? "$this->namespace.$method" : $method,
            $parameters,
            array(
                'encoding' => 'UTF-8',
                'escaping' => 'markup',
                )
        );
        $request = preg_replace(
            '%<value>\s*<string>(.*?)</string>\s*</value>%im',
            '<value>\1</value>',
            $request
        );
        $request = preg_replace(
            '%<value>\s*<int>(.*?)</int>\s*</value>%im',
            '<value><i4>\1</i4></value>',
            $request
        );
        $request = preg_replace(
            '%<value>\s*<string/>\s*</value>%im',
            '<value></value>',
            $request
        );
        $request = html_entity_decode($request);

        if ($this->logger) {
            $this->logger->debug($request);
        }
        $hdr_end = '';
        if ($this->authorisation) {
            $hdr_end = "\r\nAuthorization: Basic {$this->authorisation}\r\n";
        }
        $context = stream_context_create(
            array(
                'http' => array(
                    'method' => 'POST',
                    'header' => 'Content-Type: text/xml',
                    'content' => $request,
                ),
            )
        );

        $file = file_get_contents($this->url, false, $context);
        if ($this->logger) {
            $this->logger->debug($file);
        }
        $response = xmlrpc_decode($file);

        if (!$response) {
            throw new XmlRpcClientException(array(
                'faultString' => 'Invalid response from ' . $this->url,
            ));
        }

        if (xmlrpc_is_fault($response)) {
            throw new XmlRpcClientException($response);
        }

        return $response;
    }
}
