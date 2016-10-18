<?php
namespace AppBundle;

use AppBundle\Entity\Client;
use AppBundle\Entity\Wallet;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Faker\Factory;

class ClientManager implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function createClient($username, $country, $city, $currency)
    {
        $faker = Factory::create('en_US');

        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->getConnection()->beginTransaction();

        try {
            $client = new Client();
            $client
                ->setUsername($username)
                ->setEmail(strtolower($faker->email))
                ->setCity($city)
                ->setCountry($country)
                ->addRole('ROLE_USER')
                ->setPlainPassword('123456')
                ->setEnabled(true);
            $em->persist($client);
            $em->flush();

            $wallet = new Wallet();
            $wallet
                ->setClient($client)
                ->setCurrency($currency)
                ->setRest(0);

            $em->persist($wallet);
            $em->flush();
            $em->getConnection()->commit();
            $client->setWallet($wallet);

            return $client;
        } catch(\Exception $e) {
            $em->getConnection()->rollBack();
            throw  $e;
        }
    }
}