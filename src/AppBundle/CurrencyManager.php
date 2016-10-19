<?php
namespace AppBundle;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Intl\Intl;

class CurrencyManager implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * Список всех валют
     *
     * @return array
     */
    public function getAllCurrencyChoices()
    {
        return array_flip(Intl::getCurrencyBundle()->getCurrencyNames());
    }

    /**
     * Список валют для кошельков
     *
     * @return array
     */
    public function getWalletCurrencyChoices()
    {
        $walletCurrencies = array_flip($this->container->getParameter('app_currency.wallet_currencies'));

        return array_flip(array_intersect_key(Intl::getCurrencyBundle()->getCurrencyNames(), $walletCurrencies));
    }

    /**
     * Список провайдеров котировок валют
     *
     * @return array
     */
    public function getRateProviderChoices()
    {
        return $this->container->getParameter('app_currency.rate_providers');
    }

    /**
     * Адаптер конвертера валют
     *
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

    /**
     * @param $code
     * @return null|object|\RedCode\Currency\ICurrency
     */
    public function getCurrency($code)
    {
        return $this->container->get('redcode.currency.manager')->getCurrency($code);
    }
}
