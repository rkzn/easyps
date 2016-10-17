<?php
/**
 * This file is part of the FOSRestByExample package.
 *
 * (c) Santiago Diaz <santiago.diaz@me.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace AppBundle\Controller;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\VarDumper\VarDumper;

class ApiRestController extends FOSRestController
{
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
     * Add RateUsd from the submitted data.<br/>
     *
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     * @RequestParam(name="currency", nullable=false, strict=true, description="Currency.")
     * @RequestParam(name="rate", nullable=false, strict=true, description="Rate.")
     * @RequestParam(name="date", nullable=false, strict=true, description="Date.")
     *
     * @return View
     */
    public function postRateAction(ParamFetcher $paramFetcher)
    {
        VarDumper::dump($paramFetcher->all());
        die();
    }

    /**
     * Transfer money from the submitted data.<br/>
     *
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     * @RequestParam(name="currency", nullable=false, strict=true, description="Currency.")
     * @RequestParam(name="amount", nullable=false, strict=true, description="Amount.")
     * @RequestParam(name="receiver", nullable=false, strict=true, description="Receiver.")
     *
     * @return View
     */
    public function postTransferAction(ParamFetcher $paramFetcher)
    {
        VarDumper::dump($paramFetcher->all());
        die();
    }

    /**
     * Deposit wallet from the submitted data.<br/>
     *
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     * @RequestParam(name="currency", nullable=false, strict=true, description="Currency.")
     * @RequestParam(name="amount", nullable=false, strict=true, description="Amount.")
     * @RequestParam(name="receiver", nullable=false, strict=true, description="Receiver.")
     *
     * @return View
     */
    public function postDepositWalletAction(ParamFetcher $paramFetcher)
    {
        VarDumper::dump($paramFetcher->all());
        die();
    }

    /**
     * Create a User from the submitted data.<br/>
     *
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     * @RequestParam(name="username", nullable=false, strict=false, description="Username.")
     * @RequestParam(name="counrty", nullable=false, strict=false, description="Country.")
     * @RequestParam(name="city", nullable=false, strict=false, description="City.")
     * @RequestParam(name="currency", nullable=false, strict=false, description="Currency.")
     *
     * @return View
     */
    public function postUserAction(ParamFetcher $paramFetcher)
    {
        VarDumper::dump($paramFetcher->all());
        die();

        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->createUser();
        $user->setUsername($paramFetcher->get('username'));
        $user->setCountry($paramFetcher->get('country'));
        $user->setCity($paramFetcher->get('city'));
        $user->setEnabled(true);
        $user->addRole('ROLE_API');
        $view = View::create();
        $errors = $this->get('validator')->validate($user, array('Registration'));
        if (count($errors) == 0) {
            $userManager->updateUser($user);
            $view->setData($user)->setStatusCode(200);
            return $view;
        } else {
            $view = $this->getErrorsView($errors);
            return $view;
        }
    }
}