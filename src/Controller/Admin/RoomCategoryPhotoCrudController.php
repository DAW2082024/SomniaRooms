<?php

namespace App\Controller\Admin;

use App\Entity\RoomCategoryPhoto;
use App\Entity\RoomCategoryPhotoKinds;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{TextField, ChoiceField};

class RoomCategoryPhotoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RoomCategoryPhoto::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('path');
        yield TextField::new('altText');
        yield ChoiceField::new('kind')->setChoices(['Main' => 'main', 'Bathroom' => 'bathroom', 'Other' => 'other']);
    }
    
}