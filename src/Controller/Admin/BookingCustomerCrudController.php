<?php

namespace App\Controller\Admin;

use App\Entity\BookingCustomer;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class BookingCustomerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return BookingCustomer::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('Name');
        yield TextField::new('Surname');
        yield TextField::new('Email');
        yield TextField::new('PhoneNumber');
    }
}
