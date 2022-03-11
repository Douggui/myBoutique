<?php

namespace App\Form;

use App\Entity\Comment;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('createdAt')
            ->add('content',TextareaType::class,['label'=>false,'attr'=>[
                'placeholder'=>'votre commentaire'
            ]])
            ->add('rating',IntegerType::class,['label'=>false,'attr'=>[
                'placeholder'=>'votre nôte sur 5',
                'min'=>0,
                'max'=>5
            ]])
            ->add('submit',SubmitType::class,['label'=>'Valider le commentaire','attr'=>['class'=>'btn btn-info col-12']])
            //->add('product')
            //->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
