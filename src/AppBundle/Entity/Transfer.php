<?php

namespace AppBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * Transfer
 *
 * @GRID\Source(columns="id, sourceWallet.client.username, sourceWallet.currency, destinationWallet.client.username, destinationWallet.currency, amount, currency, createdAt, state ")
 */
class Transfer
{
    const STATE_PENDING = 'pending'; // Ожидание выполнения перевода
    const STATE_PROCESS = 'process'; // Перевод взят в обработку
    const STATE_ACCEPT = 'accept'; // Деньги списаны с отправителя
    const STATE_COMPLETE = 'complete'; // Деньги зачислены получателю
    const STATE_ERROR = 'error'; // Ошибка в процессе обработки

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var string
     */
    private $amount;

    /**
     * @var string
     */
    private $state = 'pending';

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $history;

    /**
     * @var \AppBundle\Entity\Wallet
     *
     * @GRID\Column(field="sourceWallet.currency", title="From Currency")
     * @GRID\Column(field="sourceWallet.client.username", title="From")
     */
    private $sourceWallet;

    /**
     * @var \AppBundle\Entity\Wallet
     *
     * @GRID\Column(field="destinationWallet.currency", title="To Currency")
     * @GRID\Column(field="destinationWallet.client.username", title="To")
     */
    private $destinationWallet;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->history = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Transfer
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Transfer
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set currency
     *
     * @param string $currency
     *
     * @return Transfer
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
     * Set amount
     *
     * @param string $amount
     *
     * @return Transfer
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return Transfer
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Add history
     *
     * @param \AppBundle\Entity\TransferHistory $history
     *
     * @return Transfer
     */
    public function addHistory(\AppBundle\Entity\TransferHistory $history)
    {
        $this->history[] = $history;

        return $this;
    }

    /**
     * Remove history
     *
     * @param \AppBundle\Entity\TransferHistory $history
     */
    public function removeHistory(\AppBundle\Entity\TransferHistory $history)
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

    /**
     * Set sourceWallet
     *
     * @param \AppBundle\Entity\Wallet $sourceWallet
     *
     * @return Transfer
     */
    public function setSourceWallet(\AppBundle\Entity\Wallet $sourceWallet = null)
    {
        $this->sourceWallet = $sourceWallet;

        return $this;
    }

    /**
     * Get sourceWallet
     *
     * @return \AppBundle\Entity\Wallet
     */
    public function getSourceWallet()
    {
        return $this->sourceWallet;
    }

    /**
     * Set destinationWallet
     *
     * @param \AppBundle\Entity\Wallet $destinationWallet
     *
     * @return Transfer
     */
    public function setDestinationWallet(\AppBundle\Entity\Wallet $destinationWallet = null)
    {
        $this->destinationWallet = $destinationWallet;

        return $this;
    }

    /**
     * Get destinationWallet
     *
     * @return \AppBundle\Entity\Wallet
     */
    public function getDestinationWallet()
    {
        return $this->destinationWallet;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->setUpdatedAt(new \DateTime());
    }
}
