<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Wallet
 */
class Wallet
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
     * @var float
     */
    private $rest;

    /**
     * @var \AppBundle\Entity\Client
     */
    private $client;


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
     * @return Wallet
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
     * Set rest
     *
     * @param float $rest
     * @return Wallet
     */
    public function setRest($rest)
    {
        $this->rest = $rest;

        return $this;
    }

    /**
     * Get rest
     *
     * @return float 
     */
    public function getRest()
    {
        return $this->rest;
    }

    /**
     * Set client
     *
     * @param \AppBundle\Entity\Client $client
     * @return Wallet
     */
    public function setClient(\AppBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \AppBundle\Entity\Client 
     */
    public function getClient()
    {
        return $this->client;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $history;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->history = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add history
     *
     * @param \AppBundle\Entity\WalletHistory $history
     * @return Wallet
     */
    public function addHistory(\AppBundle\Entity\WalletHistory $history)
    {
        $this->history[] = $history;

        return $this;
    }

    /**
     * Remove history
     *
     * @param \AppBundle\Entity\WalletHistory $history
     */
    public function removeHistory(\AppBundle\Entity\WalletHistory $history)
    {
        $this->history->removeElement($history);
    }

    /**
     * Get history
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHistory()
    {
        return $this->history;
    }
}
