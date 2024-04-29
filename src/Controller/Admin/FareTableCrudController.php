<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{AssociationField, TextField, DateField};

use App\Entity\FareTable;
use App\Entity\RoomCategory;
use App\Controller\Admin\RoomCategoryCrudController;

class FareTableCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FareTable::class;
    }

    public function configureFields(string $pageName): iterable
    {
        //$catRepo = $this->entityManager->getRepository(SomeEntity::class);

        yield AssociationField::new('roomCategory');

        yield DateField::new('startDate');
        yield DateField::new('endDate');

        yield TextField::new('comment');
    }
}
