<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UpdatePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => false,
                'disabled' => true
            ])
            ->add('lastName', TextType::class, [
                'label' => false,
                'disabled' => true
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'disabled' => true,
            ])
            //->add('roles')
            //->add('password')
            ->add('oldPassword', PasswordType::class, [
                'label' => false,
                'attr' => ['placeholder' => "Entrer l'ancien mdp"]
            ])
            ->add('newPassword', PasswordType::class, [
                'label' => false,
                'attr' => ['placeholder' => "Entrer le nouveau mdp"]
            ])
            ->add('confirmNewPassword', PasswordType::class, [
                'label' => false,
                'attr' => ['placeholder' => "confirmer votre mdp"]
            ])
            ->add('submit', SubmitType::class, ['label' => 'Modification mdp', 'attr' => ['class' => 'col-12 btn btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
