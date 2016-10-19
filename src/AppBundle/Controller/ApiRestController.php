<?php

namespace AppBundle\Controller;

use AppBundle\Common\Amount;
use AppBundle\Entity\Client;
use AppBundle\Exception\ClientNotFoundException;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use RedCode\CurrencyRateBundle\Command\LoadCurrencyRatesCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\Request;

class ApiRestController extends FOSRestController
{
    /**
     * @return static
     */
    public function getUsersAction()
    {
        $userManager = $this->container->get('fos_user.user_manager');
        $entity = $userManager->findUsers();
        if (!$entity) {
            throw $this->createNotFoundException('Data not found.');
        }
        $view = View::create();
        $view->setData($entity)->setStatusCode(200);
        return $view;
    }

    /**
     * Load Currency Rates.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when error"
     *   }
     * )
     *
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     * @RequestParam(name="provider", nullable=false, strict=true, description="Provider. (cbr|ecb)")
     * @RequestParam(name="date", nullable=false, strict=true, description="Date. (Y-m-d)")
     *
     * @return View
     */
    public function postRateAction(Request $request, ParamFetcher $paramFetcher)
    {
        $view = View::create();
        $view->setStatusCode(200)->setData(['params' => $paramFetcher->getParams()]);
        try {
            $command = new LoadCurrencyRatesCommand();
            $command->setContainer($this->container);
            $command->run(new ArrayInput([
                'providerName' => $paramFetcher->get('provider'),
                'date' => $paramFetcher->get('date')
            ]), new NullOutput());
        } catch (\Exception $e) {
            $view->setStatusCode(404)->setData(['error' => $e->getMessage()]);
        }

        return $view;
    }

    /**
     * Transfer money.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when error"
     *   }
     * )
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     * @RequestParam(name="currency", nullable=false, strict=true, description="Currency.")
     * @RequestParam(name="amount", nullable=false, strict=true, description="Amount.")
     * @RequestParam(name="source", nullable=false, strict=true, description="Source Client Name")
     * @RequestParam(name="destination", nullable=false, strict=true, description="Destination Client Name")
     *
     * @return View
     */
    public function postTransferAction(Request $request, ParamFetcher $paramFetcher)
    {
        $clientManager = $this->container->get('app_client');
        $walletManager = $this->container->get('app_wallet');

        try {
            /** @var Client $source */
            $source = $clientManager->getClientByName($paramFetcher->get('source'));
            if (!$source) {
                throw new ClientNotFoundException($paramFetcher->get('source'));
            }

            /** @var Client $destination */
            $destination = $clientManager->getClientByName($paramFetcher->get('destination'));
            if (!$destination) {
                throw new ClientNotFoundException($paramFetcher->get('destination'));
            }

            /** @var Amount $amount */
            $amount = new Amount($paramFetcher->get('amount'), $paramFetcher->get('currency'));

            $walletManager->transferMoney($source->getWallet(), $destination->getWallet(), $amount);
        } catch (\Exception $e) {
            throw $this->createNotFoundException($e->getMessage());
        }
    }

    /**
     * Deposit wallet from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when error"
     *   }
     * )
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     * @RequestParam(name="currency", nullable=false, strict=true, description="Currency.")
     * @RequestParam(name="amount", nullable=false, strict=true, description="Amount.")
     * @RequestParam(name="clientName", nullable=false, strict=true, description="Client Name.")
     *
     * @return View
     */
    public function postDepositWalletAction(Request $request, ParamFetcher $paramFetcher)
    {
        $clientManager = $this->container->get('app_client');
        $walletManager = $this->container->get('app_wallet');
        $view = View::create();
        $view->setStatusCode(200)->setData(['params' => $paramFetcher->getParams()]);
        try {
            /** @var Client $client */
            if (!$client = $clientManager->getClientByName($paramFetcher->get('clientName'))) {
                throw new ClientNotFoundException($paramFetcher->get('clientName'));
            }

            /** @var Amount $amount */
            $amount = new Amount($paramFetcher->get('amount'), $paramFetcher->get('currency'));

            // Отправляем деньги
            $walletManager->changeRest($client->getWallet(), $amount);

        } catch (\Exception $e) {
            $view->setStatusCode(404)->setData(['error' => $e->getMessage()]);
        }

        return $view;
    }

    /**
     * Create a Client from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when error"
     *   }
     * )
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     * @RequestParam(name="username", nullable=false, strict=true, description="Username.")
     * @RequestParam(name="country", nullable=false, strict=true, description="Country.")
     * @RequestParam(name="city", nullable=false, strict=true, description="City.")
     * @RequestParam(name="currency", nullable=false, strict=true, description="Currency.")
     *
     * @return View
     */
    public function postClientAction(Request $request, ParamFetcher $paramFetcher)
    {
        $clientManager = $this->container->get('app_client');
        $view = View::create();

        try {
            /** @var Client $client */
            $client = $clientManager->createClient(
                $paramFetcher->get('username'),
                $paramFetcher->get('country'),
                $paramFetcher->get('city'),
                $paramFetcher->get('currency')
            );

            $view->setData($client)->setStatusCode(200);

        } catch (\Exception $e) {
            $view->setData(['error' => $e->getMessage()])->setStatusCode(404);
        }

        return $view;
    }
}