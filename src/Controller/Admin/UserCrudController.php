<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController

{

    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {


        $this->passwordHasher = $passwordHasher;
    }



    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setFormOptions([
            'validation_groups' => ['registration']
        ]);
    }

    public function configureFields(string $pageName): iterable
    {
        return [

            TextField::new('firstName')->setLabel('prenom'),
            TextField::new('lastName')->setLabel('nom'),
            EmailField::new('email')->setLabel('email'),
            BooleanField::new('active'),
            ChoiceField::new('roles')->setChoices(['Admin' => 'ROLE_ADMIN', 'utilisateur' => 'ROLE_USER'])->allowMultipleChoices(),
            TextField::new('password')->setFormType(PasswordType::class)->setLabel('mot de passe')->onlyWhenCreating(),
            TextField::new('confirmPassword')->setFormType(PasswordType::class)->setLabel('confirmation mot  de passe')->onlyWhenCreating(),




        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {

        $entityInstance->setPassword($this->passwordHasher->hashPassword($entityInstance, $entityInstance->getPassword()));

        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }
}
