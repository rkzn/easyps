<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

class UsersController extends FOSRestController
{
    public function getUsersAction()
    {
        $data = []; // get data, in this case list of users.
        $view = $this->view($data, 200)
            ->setTemplate("MyBundle:Users:getUsers.html.twig")
            ->setTemplateVar('users')
        ;

        return $this->handleView($view);
    }

    public function redirectAction()
    {
        $view = $this->redirectView($this->generateUrl('app_home_page'), 301);
//        $view = $this->routeRedirectView('some_route', array(), 301);

        return $this->handleView($view);
    }
}