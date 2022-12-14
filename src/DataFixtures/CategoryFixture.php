<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $category = new Category();
            $category->setName('Category ' . $i);

            $manager->persist($category);

            $this->addReference('category_' . $i, $category);
        }

        $manager->flush();
    }
}
