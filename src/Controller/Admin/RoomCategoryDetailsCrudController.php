<?php

namespace App\Controller\Admin;

use App\Entity\RoomCategoryDetails;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RoomCategoryDetailsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RoomCategoryDetails::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('detailsSection'),
            TextField::new('detailValue'),
        ];
    }
}
