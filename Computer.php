<?php
class Computer implements \JsonSerializable
{
    private $_name;
    private $_ipAddress;
    private $_port;

    public function jsonSerialize()
    {
        $vars = get_object_vars($this);

        return $vars;
    }

    function __construct($name, $ip, $port)
    {
        $this->_name = $name;
        $this->_ipAddress = $ip;
        $this->_port = $port;
    }

    public function getName() {
        return $this->_name;
    }

    public function getIpAddress() {
        return $this->_ipAddress;
    }

    public function getPort() {
        return $this->_port;
    }
}
