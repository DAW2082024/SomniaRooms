<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

use App\Entity\RoomCategory;

class RoomCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RoomCategory::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            //IdField::new('id'),
            TextField::new('name'),
            TextField::new('description'),
            TextField::new('bedType'),
            IntegerField::new('maxGuestNum'),
            CollectionField::new('roomCategoryDetails')->useEntryCrudForm(),
            CollectionField::new('roomCategoryPhotos')->useEntryCrudForm()
        ];
    }
}
