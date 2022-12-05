<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Cart;
use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClientFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        for ($i = 0; $i < 10; $i++) {
            $client = new Client();
            $client->setFirstName('firstname ' . $i);
            $client->setLastName('lastname ' . $i);
            $client->setEmail('email ' . $i . '@gmail.com');
            $client->setBirthDate(new \DateTimeImmutable('-' . rand(20, 30) . ' years'));

            $address = new Address();
            $address->setStreet('street ' . $i);
            $address->setPostalCode('postalCode ' . $i);
            $address->setCity('city ' . $i);

            $client->addAddress($address);

            $cart = new Cart();

            $cart->setClient($client);
            $cart->setTotalAmount(0);
            $cart->setCreatedAt(new \DateTimeImmutable('-' . rand(1, 10) . ' days'));

            $manager->persist($client);
            $manager->persist($cart);
        }

        $manager->flush();
    }
}
