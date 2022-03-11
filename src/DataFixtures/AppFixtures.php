<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        for ($i = 1; $i < 11; $i++) {

            $categorie = new Category();
            $categorie->setName('cat-' . $i);

            $this->addReference('categorie_' . $i, $categorie);

            $manager->persist($categorie);
        }
        $manager->flush();
    }
}
