<?php

namespace App\Controller\Admin;

use App\Entity\Booking;
use App\Entity\ConfigVariable;
use App\Entity\FareTable;
use App\Entity\RoomCategory;
use App\Entity\RoomFare;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SomniaDashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/somniaDashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new ()
            ->setTitle('SomniaRooms');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::Section('Booking Management');
        yield MenuItem::linkToCrud('Bookings', 'fas fa-book', Booking::class);

        yield MenuItem::Section('Room Management');
        yield MenuItem::linkToCrud('Category', 'fas fa-layer-group', RoomCategory::class);
        yield MenuItem::linkToRoute('Room Availability', 'fas fa-calendar-check', 'admin_roomAvailability');
        yield MenuItem::linkToCrud('Fare Tables', 'fas fa-table', FareTable::class);
        yield MenuItem::linkToCrud('Room Fares', 'fas fa-tag', RoomFare::class);

        yield MenuItem::Section('App Management');
        yield MenuItem::linkToCrud('Config Vars', 'fas fa-gear', ConfigVariable::class);
        yield MenuItem::linkToCrud('Users', 'fas fa-user', User::class)
            ->setPermission("ROLE_SUPERADMIN");
    }
}
