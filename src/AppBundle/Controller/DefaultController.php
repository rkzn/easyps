<?php

namespace AppBundle\Controller;

use APY\DataGridBundle\Grid\Export\CSVExport;
use APY\DataGridBundle\Grid\Source\Entity;
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
     * @return array|\Symfony\Component\HttpFoundation\Response
     */
    public function reportAction(Request $request)
    {
        // Creates simple grid based on your entity (ORM)
        $source = new Entity('AppBundle:WalletHistory');

        // Get a grid instance
        $grid = $this->get('grid');

        // Attach the source to the grid
        $grid->setSource($source);
        $grid->addExport(new CSVExport('CSV Export', 'export'));

        // Configuration of the grid
        $grid->setLimits(array(5, 10, 15));
        $grid->setDefaultPage(1);
        $grid->setRouteUrl($this->generateUrl('app_report_page'));

        $grid->hideFilters();

        // Manage the grid redirection, exports and the response of the controller
        return $grid->getGridResponse('AppBundle::report.html.twig');
    }

    /**
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\Response
     */
    public function transfersAction(Request $request)
    {
        $source = new Entity('AppBundle:Transfer');

        $grid = $this->get('grid');
        $grid->setSource($source);
        $grid->addExport(new CSVExport('CSV Export', 'export'));
        $grid->setLimits(array(5, 10, 15));
        $grid->setDefaultPage(1);
        $grid->setRouteUrl($this->generateUrl('app_transfers_page'));

        $grid->hideFilters();

        return $grid->getGridResponse('AppBundle::transfers.html.twig');
    }
}
