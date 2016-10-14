<?php

namespace AppBundle\DataFixtures\ORM;

use UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('en_US');

        $admin = new User();
        $admin
            ->setUsername('admin')
            ->setEmail('admin@admin.com')

            ->setCity($faker->city)
            ->setCountry($faker->countryCode)

            ->addRole('ROLE_ADMIN')
            ->setPlainPassword('123456')
            ->setEnabled(true)
        ;
        $manager->persist($admin);
        $manager->flush();

        for ($b = 0; $b < 3; $b++) {
            $user = new User();
            $user
                ->setUsername(strtolower($faker->userName))
                ->setEmail(strtolower($faker->email))

                ->setCity($faker->city)
                ->setCountry($faker->countryCode)

                ->addRole('ROLE_USER')
                ->setPlainPassword('123456')
                ->setEnabled(true)
            ;
            $manager->persist($user);
            $manager->flush();
        }

        $this->addReference('admin', $admin);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
