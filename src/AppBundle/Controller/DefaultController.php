<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
     */
    public function reportAction(Request $request)
    {

    }
}
