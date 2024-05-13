<?php

namespace App\Controller\Admin;

use App\Entity\RoomFare;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

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

        $dayTypeList = ["MONDAY" => 1, "TUESDAY" => 2, "WEDNESDAY" => 3, "THURSDAY" => 4, "FRIDAY" => 5, "SATURDAY" => 6, "SUNDAY" => 0];
        yield ChoiceField::new('dayType')->setChoices($dayTypeList);
        
    }
}
