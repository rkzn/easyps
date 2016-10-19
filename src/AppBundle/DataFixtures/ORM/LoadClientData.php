<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class LoadClientData extends AbstractFixture implements OrderedFixtureInterface
{
    use ContainerAwareTrait;

    protected $clientsData = [
        [
            'username' => 'test01',
            'country' => 'RU',
            'city' => 'Moscow',
            'currency' => 'RUB',
        ],
        [
            'username' => 'test02',
            'country' => 'US',
            'city' => 'NewYork',
            'currency' => 'USD',
        ],
        [
            'username' => 'test03',
            'country' => 'DE',
            'city' => 'Berlin',
            'currency' => 'EUR',
        ]
    ];

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $clientManager = $this->container->get('app_client');

        foreach($this->clientsData as $data) {

            $clientManager->createClient(
                $data['username'],
                $data['country'],
                $data['city'],
                $data['currency']
            );
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
