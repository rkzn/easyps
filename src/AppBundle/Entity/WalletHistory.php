<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WalletHistory
 */
class WalletHistory
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $addDate;

    /**
     * @var string
     */
    private $restBefore;

    /**
     * @var string
     */
    private $restAfter;

    /**
     * @var \AppBundle\Entity\Wallet
     */
    private $wallet;


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
     * Set addDate
     *
     * @param \DateTime $addDate
     * @return WalletHistory
     */
    public function setAddDate($addDate)
    {
        $this->addDate = $addDate;

        return $this;
    }

    /**
     * Get addDate
     *
     * @return \DateTime 
     */
    public function getAddDate()
    {
        return $this->addDate;
    }

    /**
     * Set restBefore
     *
     * @param string $restBefore
     * @return WalletHistory
     */
    public function setRestBefore($restBefore)
    {
        $this->restBefore = $restBefore;

        return $this;
    }

    /**
     * Get restBefore
     *
     * @return string 
     */
    public function getRestBefore()
    {
        return $this->restBefore;
    }

    /**
     * Set restAfter
     *
     * @param string $restAfter
     * @return WalletHistory
     */
    public function setRestAfter($restAfter)
    {
        $this->restAfter = $restAfter;

        return $this;
    }

    /**
     * Get restAfter
     *
     * @return string 
     */
    public function getRestAfter()
    {
        return $this->restAfter;
    }

    /**
     * Set wallet
     *
     * @param \AppBundle\Entity\Wallet $wallet
     * @return WalletHistory
     */
    public function setWallet(\AppBundle\Entity\Wallet $wallet = null)
    {
        $this->wallet = $wallet;

        return $this;
    }

    /**
     * Get wallet
     *
     * @return \AppBundle\Entity\Wallet 
     */
    public function getWallet()
    {
        return $this->wallet;
    }
    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->setAddDate(new \DateTime());
    }
}
