<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersFixtures extends Fixture
{

    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {


        $this->passwordHasher = $passwordHasher;
    }


    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $user = new User();
        $user->setFirstname('eric')
            ->setLastname('devolder')
            ->setEmail('test@test.fr')
            ->setActive(false)
            ->setRoles(["ROLE_ADMIN"]);

        $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));

        $manager->persist($user);

        $faker = Factory::create('fr_FR'); // create a French faker

        for ($i = 0; $i <= 10; $i++) {
            $user = new User();
            $user->setFirstName($faker->firstName());
            $user->setLastName($faker->lastName());
            $user->setEmail($faker->email());
            $user->setActive(false);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));

            $manager->persist($user);
        }

        $manager->flush();
    }
}
