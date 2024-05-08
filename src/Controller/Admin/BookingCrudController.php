<?php

namespace App\Controller\Admin;

use App\Controller\Admin\BookingCustomerCrudController;
use App\Entity\Booking;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class BookingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Booking::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fieldRefNumber = TextField::new ('refNumber');
        $fieldBookingTime = DateTimeField::new ('BookingTime');
        if ($pageName == Crud::PAGE_EDIT) {
            $fieldRefNumber->setDisabled(true);
            $fieldBookingTime->setDisabled(true);
        }

        yield FormField::addFieldset('Booking details');
        yield $fieldRefNumber;
        yield $fieldBookingTime;

        yield DateField::new ('ArrivalDate');
        yield DateField::new ('DepartureDate');

        yield AssociationField::new ('bookingCustomer')->renderAsEmbeddedForm(BookingCustomerCrudController::class);
        yield CollectionField::new ('bookingRooms')->useEntryCrudForm();
    }
}
