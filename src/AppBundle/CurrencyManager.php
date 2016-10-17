<?php
namespace AppBundle;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Intl\Intl;

class CurrencyManager implements ContainerAwareInterface
{
    const USD = 'USD';
    use ContainerAwareTrait;

    public function getList()
    {
        return array_flip(Intl::getCurrencyBundle()->getCurrencyNames());
    }
}
