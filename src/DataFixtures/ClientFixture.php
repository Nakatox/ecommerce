<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ClientFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // use faker to generate data
        $faker = \Faker\Factory::create('fr_FR');

        // create 10 clients
        for ($i = 0; $i < 10; $i++) {
            $client = new Client();
            $client->setFirstName($faker->firstName);
            $client->setLastName($faker->lastName);
            $client->setEmail($faker->email);
            $client->setBirthDate($faker->dateTimeBetween('-50 years', '-18 years'));

            $address = new Address();
            $address->setStreet($faker->streetAddress);
            $address->setPostalCode($faker->postcode);
            $address->setCity($faker->city);

            $client->addAddress($address);

            $manager->persist($client);
        }

        $manager->flush();
    }
}
