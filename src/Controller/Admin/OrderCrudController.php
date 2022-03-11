<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use DateTime;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add('index', 'detail')
            ->remove('index', 'new')
            ->remove('index', 'edit')
            ->remove('index', 'delete');
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateTimeField::new('createdAt', 'Passé le :'),
            TextField::new('user.getFullName', 'client'),
            MoneyField::new('getTotal', 'total')->setCurrency('EUR'),
            TextField::new('getCarrier.getName', 'transporteur'),
            MoneyField::new('getCarrier.getPrice', 'prix transporteur')->setCurrency('EUR'),
            BooleanField::new('statut', 'payé'),
            ArrayField::new('orderDetails', 'produits acheté'),


        ];
    }
}