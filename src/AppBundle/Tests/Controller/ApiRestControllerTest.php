<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiRestControllerTest extends WebTestCase
{
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

    protected $transferData = [
        [
            'source' => 'test01',
            'destination' => 'test02',
            'amount' => 10,
            'currency' => 'USD',
        ],
        [
            'source' => 'test02',
            'destination' => 'test03',
            'amount' => 10,
            'currency' => 'USD',
        ],
        [
            'source' => 'test03',
            'destination' => 'test01',
            'amount' => 10,
            'currency' => 'EUR',
        ],
    ];

    public function testLoadCurrencyRates()
    {
        $dates = [
            new \DateTime()
        ];

        /** @var \DateTime $date */
        foreach($dates as $date) {
            $client = static::createClient();
            $client->request('POST', '/api/rates.json', [
                'provider' => 'cbr',
                'date' => $date->format('Y-m-d')
            ]);

            $this->assertEquals(200, $client->getResponse()->getStatusCode());
        }
    }

    public function testCreateClient()
    {
        $client = static::createClient();
        foreach ($this->clientsData as $data) {
            $client->request('POST', '/api/clients.json', $data);
            $this->assertEquals(200, $client->getResponse()->getStatusCode());
        }
    }

    public function testDepositWallet()
    {
        $client = static::createClient();
        foreach ($this->clientsData as $data) {
            $client->request('POST', '/api/deposits/wallets.json', [
                'clientName' => $data['username'],
                'amount' => 1000,
                'currency' => $data['currency'],
            ]);
            $this->assertEquals(200, $client->getResponse()->getStatusCode());
        }
    }

    public function testTransferMoney()
    {
        foreach ($this->transferData as $data) {
            $client = static::createClient();
            $client->request('POST', '/api/transfers.json', $data);
            $this->assertEquals(200, $client->getResponse()->getStatusCode());
        }
    }
}