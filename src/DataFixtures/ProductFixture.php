<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $product = new Product();
            $product->setName('product ' . $i);
            $product->setQuantity(rand(1, 100));
            $product->setPrice(rand(1, 100));
            $product->setDescription('description ' . $i);
            $product->setCategory($this->getReference('category_' . rand(0, 9)));
            $product->setSlug('name ' . $i . '-' . uniqid());

            $manager->persist($product);


            $this->addReference('product_' . $i, $product);
        }

        $manager->flush();
    }
}
