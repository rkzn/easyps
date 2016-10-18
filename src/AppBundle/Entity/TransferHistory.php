<?php

namespace AppBundle\Entity;

/**
 * TransferHistory
 */
class TransferHistory
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
    private $stateBefore;

    /**
     * @var string
     */
    private $stateAfter;

    /**
     * @var \AppBundle\Entity\Transfer
     */
    private $transfer;


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
     *
     * @return TransferHistory
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
     * Set stateBefore
     *
     * @param string $stateBefore
     *
     * @return TransferHistory
     */
    public function setStateBefore($stateBefore)
    {
        $this->stateBefore = $stateBefore;

        return $this;
    }

    /**
     * Get stateBefore
     *
     * @return string
     */
    public function getStateBefore()
    {
        return $this->stateBefore;
    }

    /**
     * Set stateAfter
     *
     * @param string $stateAfter
     *
     * @return TransferHistory
     */
    public function setStateAfter($stateAfter)
    {
        $this->stateAfter = $stateAfter;

        return $this;
    }

    /**
     * Get stateAfter
     *
     * @return string
     */
    public function getStateAfter()
    {
        return $this->stateAfter;
    }

    /**
     * Set transfer
     *
     * @param \AppBundle\Entity\Transfer $transfer
     *
     * @return TransferHistory
     */
    public function setTransfer(\AppBundle\Entity\Transfer $transfer = null)
    {
        $this->transfer = $transfer;

        return $this;
    }

    /**
     * Get transfer
     *
     * @return \AppBundle\Entity\Transfer
     */
    public function getTransfer()
    {
        return $this->transfer;
    }
    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->setAddDate(new \DateTime());
    }

}

