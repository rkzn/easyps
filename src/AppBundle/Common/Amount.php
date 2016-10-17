<?php
namespace AppBundle\Common;

class Amount
{
    /** @var  float */
    private $value;

    /** @var  string */
    private $currency;

    /**
     * Amount constructor.
     * @param $value
     * @param $currency
     */
    public function __construct($value, $currency)
    {
        $this->value = $value;
        $this->currency = $currency;
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }
}
