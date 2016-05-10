<?php
namespace Hakger\Paxi;

class XmlRpcClientException extends \Exception
{
    public function __construct($message, $code = 0, \Exception $previous = null)
    {
        if (is_array($message)) {
            parent::__construct($message['faultString'], $code, $previous);
        } else {
            parent::__construct($message, $code, $previous);
        }
    }
}
