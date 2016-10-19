<?php
namespace AppBundle;

use AppBundle\Entity\Client;
use AppBundle\Entity\Wallet;
use Doctrine\DBAL\Exception\DatabaseObjectExistsException;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Faker\Factory;
use Symfony\Component\Validator\Exception\ValidatorException;

class ClientManager implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function getClientByName($name)
    {
        return $this->container->get('fos_user.user_manager')->findUserByUsername($name);
    }

    /**
     * Создание клиента и его кошелька
     *
     * @param $username
     * @param $country
     * @param $city
     * @param $currency
     * @return Client
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Exception
     */
    public function createClient($username, $country, $city, $currency)
    {
        $faker = Factory::create('en_US');
        $userManager = $this->container->get('fos_user.user_manager');
        $walletManager = $this->container->get('app_wallet');

        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->getConnection()->beginTransaction();

        try {
            $client = $this->getClientByName($username);

            if (!$client) {
                /** @var Client $client */
                $client = $userManager->createUser();

                $client
                    ->setUsername($username)
                    ->setEmail(strtolower($faker->email))
                    ->setCity($city)
                    ->setCountry($country)
                    ->addRole('ROLE_USER')
                    ->setPlainPassword('123456')
                    ->setEnabled(true);

                $errors = $this->container->get('validator')->validate($client, array('Registration'));

                if (count($errors) > 0) {
                    throw new ValidatorException((string)$errors);
                }

                $userManager->updateUser($client);


                $wallet = $walletManager->createWallet($client, $currency);

                $errors = $this->container->get('validator')->validate($wallet);


                if (count($errors) > 0) {
                    throw new ValidatorException((string)$errors);
                }

                $walletManager->updateWallet($wallet);

                $client->setWallet($wallet);
            }
            $em->getConnection()->commit();
            return $client;
        } catch(\Exception $e) {
            $em->getConnection()->rollBack();
            throw  $e;
        }
    }
}