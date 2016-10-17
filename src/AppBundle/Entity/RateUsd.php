<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RateUsd
 */
class RateUsd
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var string
     */
    private $rateValue;

    /**
     * @var \DateTime
     */
    private $rateDate;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set currency
     *
     * @param string $currency
     * @return RateUsd
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string 
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set rateValue
     *
     * @param string $rateValue
     * @return RateUsd
     */
    public function setRateValue($rateValue)
    {
        $this->rateValue = $rateValue;

        return $this;
    }

    /**
     * Get rateValue
     *
     * @return string 
     */
    public function getRateValue()
    {
        return $this->rateValue;
    }

    /**
     * Set rateDate
     *
     * @param \DateTime $rateDate
     * @return RateUsd
     */
    public function setRateDate($rateDate)
    {
        $this->rateDate = $rateDate;

        return $this;
    }

    /**
     * Get rateDate
     *
     * @return \DateTime 
     */
    public function getRateDate()
    {
        return $this->rateDate;
    }
}
