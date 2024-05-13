<?php

namespace App\Controller\Admin;

use App\Entity\ConfigVariable;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class ConfigVariableCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ConfigVariable::class;
    }


    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('section');
        yield TextField::new('key');
        yield TextField::new('value');
    }
}
