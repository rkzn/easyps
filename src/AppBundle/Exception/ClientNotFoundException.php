<?php

namespace AppBundle\Exception;

class ClientNotFoundException extends \Exception
{
    public function __construct($name)
    {
        $message = sprintf('Client %s not found', $name);
        parent::__construct($message, 0, null);
    }
}