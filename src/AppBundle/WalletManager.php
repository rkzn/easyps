<?php
namespace AppBundle;

use AppBundle\Common\Amount;
use AppBundle\Entity\Wallet;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class WalletManager implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /** @var  EntityManager */
    protected $entityManager;

    /** @var  CurrencyManager */
    protected $currencyManager;

    /**
     * WalletManager constructor.
     * @param $entityManager
     * @param $currencyManager
     */
    public function __construct(EntityManager $entityManager, CurrencyManager $currencyManager)
    {
        $this->entityManager = $entityManager;
        $this->currencyManager = $currencyManager;
    }

    public function updateWallet(Wallet $wallet)
    {

    }

    public function transferMoney(Wallet $source, Wallet $destination, Amount $amount)
    {

    }
}