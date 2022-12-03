<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $category = new Category();
            $category->setName($faker->word);

            $manager->persist($category);

            $this->addReference('category_' . $i, $category);
        }

        $manager->flush();
    }
}
