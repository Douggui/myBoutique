<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Carrier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $user = $options['user'];
        $builder
            //->add('field_name')
            ->add('addresses', EntityType::class, [

                //'choice_label' => 'name',
                'choices' => $user->getAddresses(),
                'class' => Address::class,
                'label' => 'Choisissez-votre adresse',
                'expanded' => true,
                'multiple' => false,

            ])
            ->add('carrier', EntityType::class, [

                'class' => Carrier::class,
                'label' => 'Choisissez-votre transporteur',
                'expanded' => true,
                'multiple' => false,


            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider ma commande',
                'attr' => [
                    'class' => 'btn btn-info col-12'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here

            'user' => []

        ]);
    }
}
