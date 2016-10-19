<?php

namespace AppBundle\Exception;

class CurrencyInvalidException extends \Exception
{
    public function __construct($currency)
    {
        $message = sprintf('Currency %s is invalid', $currency);
        parent::__construct($message);
    }
}
