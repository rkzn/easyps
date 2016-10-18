<?php
namespace AppBundle;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Intl\Intl;

class CurrencyManager implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function getAllCurrencyChoices()
    {
        return array_flip(Intl::getCurrencyBundle()->getCurrencyNames());
    }

    public function getWalletCurrencyChoices()
    {
        $walletCurrencies = array_flip($this->container->getParameter('app_currency.wallet_currencies'));

        return array_flip(array_intersect_key(Intl::getCurrencyBundle()->getCurrencyNames(), $walletCurrencies));
    }

    public function getRateProviderChoices()
    {
        return $this->container->getParameter('app_currency.rate_providers');
    }
}
