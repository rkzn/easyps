<?php
namespace AppBundle;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Intl\Intl;

class CurrencyManager implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @return array
     */
    public function getAllCurrencyChoices()
    {
        return array_flip(Intl::getCurrencyBundle()->getCurrencyNames());
    }

    /**
     * @return array
     */
    public function getWalletCurrencyChoices()
    {
        $walletCurrencies = array_flip($this->container->getParameter('app_currency.wallet_currencies'));

        return array_flip(array_intersect_key(Intl::getCurrencyBundle()->getCurrencyNames(), $walletCurrencies));
    }

    /**
     * @return array
     */
    public function getRateProviderChoices()
    {
        return $this->container->getParameter('app_currency.rate_providers');
    }

    /**
     * @param $from
     * @param $to
     * @param $value
     * @return float
     * @throws \RedCode\Currency\Rate\Exception\ProviderNotFoundException
     * @throws \RedCode\Currency\Rate\Exception\RateNotFoundException
     */
    public function convert($from, $to, $value)
    {
        return $this->container->get('redcode.currency.rate.converter')->convert($from, $to, $value);
    }
}
