<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Product;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProductsFixtures extends Fixture
{



    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);



        $faker = Factory::create('fr_FR'); // create a French faker

        for ($i = 0; $i <= 10; $i++) {
            $product = new Product();
            $product->setName($faker->realText(20));
            $product->setDescription($faker->realText());
            $product->setPrice($faker->numberBetween(0, 20000));

            // $product->setSlug($faker->slug(2));
            $product->setSubtitle($faker->realText(50));
            $product->setIllustration($faker->image('public/uploads', 360, 360, 'PRODUCT', false, true, 'image générer par faker', true));
            $product->setCategory($this->getReference('categorie_' . $faker->numberBetween(1, 10)));



            $manager->persist($product);
        }

        $manager->flush();
    }
}
