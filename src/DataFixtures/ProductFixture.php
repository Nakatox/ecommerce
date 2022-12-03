<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $product = new Product();
            $product->setName($faker->word);
            $product->setQuantity($faker->numberBetween(0, 100));
            $product->setPrice($faker->numberBetween(0, 1000));
            $product->setDescription($faker->paragraph);
            $product->setCategory($this->getReference('category_' . $faker->numberBetween(0, 9)));

            $manager->persist($product);
        }

        $manager->flush();
    }
}
