<?php

namespace App\Form;


use App\Entity\Category;

use App\Entity\SearchForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('string', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'filtrer par nom de produit',
                    'onchange' => "this.closest('form').submit()"
                ]
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'attr' => ['onchange' => "this.closest('form').submit()"]


            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchForm::class,
            'method' => 'GET',
            'csrf_protection' => false
            // Configure your form options here
        ]);
    }
}
