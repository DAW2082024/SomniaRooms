<?php

namespace App\Controller\Admin;

use App\Entity\RoomFare;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RoomFareCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RoomFare::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fieldFareTable = AssociationField::new ('fareTable')->setRequired(true);
        if ($pageName == Crud::PAGE_EDIT) {
            $fieldFareTable->setDisabled(true);
        }
        yield $fieldFareTable;

        yield NumberField::new ('guestNumber');
        yield MoneyField::new ('fareAmount')->setCurrency('EUR');

        yield TextField::new('dayType')->setMaxLength(2);
        

    }
}
