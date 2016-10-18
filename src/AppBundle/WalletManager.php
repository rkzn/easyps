<?php
namespace AppBundle;

use AppBundle\Common\Amount;
use AppBundle\Entity\Wallet;
use AppBundle\Exception\WrongCurrencyException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;
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

    public function transferMoney(Wallet $source, Wallet $destination, Amount $amount)
    {
        if (!in_array($amount->getCurrency(), [$source->getCurrency(), $destination->getCurrency()])) {
            throw new WrongCurrencyException(sprintf('Wrong Amount currency %s', $amount->getCurrency()));
        }

        $this->entityManager->getConnection()->beginTransaction();
        try {
            $srcAmount = new Amount($amount->getValue() * -1, $amount->getCurrency());
            $dstAmount = new Amount($amount->getValue(), $amount->getCurrency());
            $this->changeRest($source, $srcAmount);
            $this->changeRest($destination, $dstAmount);
            $this->entityManager->getConnection()->commit();

        } catch (Exception $e) {
            $this->entityManager->getConnection()->rollBack();
            throw $e;
        }
    }

    /**
     * @param Wallet $wallet
     * @param Amount $amount
     */
    public function changeRest(Wallet $wallet, Amount $amount)
    {
        if ($wallet->getCurrency() == $amount->getCurrency()) {
            $amountValue = $amount->getValue();
        } else {
            $amountValue = $this->currencyManager->convert(
                $amount->getCurrency(),
                $wallet->getCurrency(),
                $amount->getValue()
            );
        }

        $oldValue = $wallet->getRest();
        $newValue = $oldValue + $amountValue;

        if ($newValue < 0) {
            $newValue = 0;
        }

        $wallet->setRest($newValue);
        $this->entityManager->persist($wallet);
        $this->entityManager->flush($wallet);
    }
}