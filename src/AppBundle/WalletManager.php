<?php
namespace AppBundle;

use AppBundle\Common\Amount;
use AppBundle\Entity\Client;
use AppBundle\Entity\Transfer;
use AppBundle\Entity\Wallet;
use AppBundle\Exception\CurrencyInvalidException;
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

    public function createWallet(Client $client, $currency, $rest = 0)
    {
        $wallet = new Wallet();
        $wallet->setClient($client);
        $wallet->setCurrency($currency);
        $wallet->setRest($rest);

        return $wallet;
    }

    /**
     * @param Wallet $wallet
     * @param bool $andFlush
     */
    public function updateWallet(Wallet $wallet, $andFlush = true)
    {
        $this->entityManager->persist($wallet);
        if ($andFlush) {
            $this->entityManager->flush();
        }
    }

    public function createTransfer(Wallet $src, Wallet $dst, $amount, $currency)
    {
        $transfer = new Transfer();
        $transfer->setAmount($amount);
        $transfer->setCurrency($currency);
        $transfer->setState(Transfer::STATE_PENDING);
        $transfer->setSourceWallet($src);
        $transfer->setDestinationWallet($dst);

        return $transfer;
    }

    /**
     * @param Transfer $transfer
     * @param bool $andFlush
     */
    public function updateTransfer(Transfer $transfer, $andFlush = true)
    {
        $this->entityManager->persist($transfer);
        if ($andFlush) {
            $this->entityManager->flush();
        }
    }

    /**
     * Обработка перевода
     *
     * - Сверяем валюту суммы перевода с валютой отправителя или валютой получателя
     * - В зависимости от текущего статуса заявки выполняем нужное действие
     *   STATE_PENDING - меняем статус на STATE_PROCESS
     *   STATE_PROCESS - Списываем деньги и меняем статус на STATE_ACCEPT
     *   STATE_ACCEPT - Зачисляем деньги получателю и меняем статус на STATE_COMPLETE
     *
     * - В случае возникновения ошибки, откатываем изменения
     *
     * @param Transfer $transfer
     */
    public function executeTransfer(Transfer $transfer)
    {
        $source = $transfer->getSourceWallet();
        $destination = $transfer->getDestinationWallet();

        if (!in_array($transfer->getCurrency(), [$source->getCurrency(), $destination->getCurrency()])) {
            throw new CurrencyInvalidException($transfer->getCurrency());
        }

        $this->entityManager->getConnection()->beginTransaction();

        try {
            $srcAmount = new Amount($transfer->getAmount() * -1, $transfer->getCurrency());
            $dstAmount = new Amount($transfer->getAmount(), $transfer->getCurrency());

            if ($transfer->getState() == Transfer::STATE_PENDING) {
                $transfer->setState(Transfer::STATE_PROCESS);
                $this->entityManager->persist($transfer);
                $this->entityManager->flush($transfer);
            }

            if ($transfer->getState() == Transfer::STATE_PROCESS) {
                $this->changeRest($source, $srcAmount);
                $transfer->setState(Transfer::STATE_ACCEPT);
                $this->entityManager->persist($transfer);
                $this->entityManager->flush($transfer);
            }

            if ($transfer->getState() == Transfer::STATE_ACCEPT) {
                $this->changeRest($destination, $dstAmount);
                $transfer->setState(Transfer::STATE_COMPLETE);
                $this->entityManager->persist($transfer);
                $this->entityManager->flush($transfer);
            }

            $this->entityManager->getConnection()->commit();

        } catch (Exception $e) {
            $this->entityManager->getConnection()->rollBack();
            // todo: Сохранить в лог и отправить письмо с ошибкой
            throw $e;
        }
    }

    /**
     * @param Wallet $source
     * @param Wallet $destination
     * @param Amount $amount
     * @throws CurrencyInvalidException
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function transferMoney(Wallet $source, Wallet $destination, Amount $amount)
    {
        if (!in_array($amount->getCurrency(), [$source->getCurrency(), $destination->getCurrency()])) {
            throw new CurrencyInvalidException($amount->getCurrency());
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