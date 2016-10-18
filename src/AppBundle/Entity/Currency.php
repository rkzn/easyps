<?php
namespace AppBundle\Entity;

/**
 * @ORM\Entity
 */
class Currency extends \RedCode\CurrencyRateBundle\Entity\Currency
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     */
    protected $code;

    /**
     * Id of currency
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get 3 symbols currency code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
}