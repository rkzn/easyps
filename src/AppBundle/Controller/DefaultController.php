<?php

namespace AppBundle\Controller;

use AppBundle\Common\Amount;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\VarDumper\VarDumper;

class DefaultController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $registrationForm = $this->createForm('registration_form');
        $rateUsdForm = $this->createForm('rate_usd_form');
        $depositForm = $this->createForm('deposit_wallet_form');
        $transferForm = $this->createForm('transfer_money_form');

        return $this->render('AppBundle::index.html.twig', [
            'registrationForm' => $registrationForm->createView(),
            'rateUsdForm' => $rateUsdForm->createView(),
            'depositForm' => $depositForm->createView(),
            'transferForm' => $transferForm->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function testAction(Request $request)
    {
        $clientManager = $this->container->get('app_client');
        $currencyManager = $this->container->get('app_currency');
        $walletManager = $this->container->get('app_wallet');

        $source = $clientManager->getClientByName('mrau');
        $dest = $clientManager->getClientByName('hcole');

        $amount = new Amount(10, 'USD');

        $walletManager->changeRest($source->getWallet(), $amount);
        $walletManager->changeRest($dest->getWallet(), $amount);

        VarDumper::dump($source->getWallet());
        VarDumper::dump($dest->getWallet());
        die();
    }

    /**
     * @param Request $request
     */
    public function reportAction(Request $request)
    {
        return $this->render('AppBundle::report.html.twig');
    }
}
