<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }


    public function configureFields(string $pageName): iterable
    {

        return [
            // IdField::new('id'),
            //TextField::new('title'),
            AssociationField::new('user')->onlyOnIndex()->setLabel('uilisateurs'),
            TextEditorField::new('content')->setLabel('commentaires'),
            IntegerField::new('rating',)->setLabel('note'),
            DateField::new('createdAt')->setLabel('date de commentaire')

        ];
    }
}
