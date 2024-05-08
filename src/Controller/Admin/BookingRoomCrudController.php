<?php

namespace App\Controller\Admin;

use App\Controller\Admin\RoomCategoryCrudController;
use App\Entity\BookingRoom;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

class BookingRoomCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return BookingRoom::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new ('RoomCategory')->setCrudController(RoomCategoryCrudController::class);
        yield NumberField::new ('GuestNumber');
        yield MoneyField::new ('RoomPrice')->setCurrency("EUR");
        yield NumberField::new ('Amount');
    }
}
