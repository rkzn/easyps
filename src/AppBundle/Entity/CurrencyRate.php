<?php

namespace AppBundle\Entity;

/**
 * @ORM\Entity
 */
class CurrencyRate extends \RedCode\CurrencyRateBundle\Entity\CurrencyRate
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var \DateTime
     */
    protected $date;

    /**
     * @var int
     */
    protected $nominal;

    /**
     * @var float
     */
    protected $rate;

    /**
     * @var \RedCode\Currency\ICurrency
     * @ORM\ManyToOne(targetEntity="Currency")
     * @ORM\JoinColumn(name="currency_id", referencedColumnName="id")
     */
    protected $currency;

    /**
     * @var string
     */
    protected $providerName;
}
