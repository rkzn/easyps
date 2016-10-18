<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Client;
use AppBundle\Entity\Wallet;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class LoadClientData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('en_US');

        for ($b = 0; $b < 3; $b++) {
            $client = new Client();
            $client
                ->setUsername(strtolower($faker->userName))
                ->setEmail(strtolower($faker->email))

                ->setCity($faker->city)
                ->setCountry($faker->countryCode)

                ->addRole('ROLE_USER')
                ->setPlainPassword('123456')
                ->setEnabled(true)
            ;
            $manager->persist($client);
            $manager->flush();

            $wallet = new Wallet();
            $wallet
                ->setClient($client)
                ->setCurrency($faker->currencyCode)
                ->setRest(0);

            $manager->persist($wallet);
            $manager->flush();
            $client->setWallet($wallet);

        }
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
