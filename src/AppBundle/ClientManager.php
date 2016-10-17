<?php
namespace AppBundle;

use AppBundle\Entity\Client;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class ClientManager implements ContainerAwareInterface
{
    use ContainerAwareTrait;


}