<?php

namespace App\Controller\Admin;

use App\Repository\RoomAvailabilityRepository;
use App\Repository\RoomCategoryRepository;
use App\Entity\RoomAvailability;
use App\Entity\RoomCategory;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Field\{TextField, DateField, AssociationField, NumberField};

#[IsGranted("ROLE_ADMIN")]
class RoomAvailabilityAdminController extends AbstractController
{

    #[Route('/admin/roomAvailability', name: 'admin_roomAvailability')]
    public function index(RoomAvailabilityRepository $repo, RoomCategoryRepository $catRepo): Response
    {
        $categoryList = $catRepo->findAll();

        return $this->render('admin/room_availability/index.html.twig', [
            'roomCategoryList' => $categoryList,
        ]);
    }

    #[Route('/admin/roomAvailability/{categoryId}', name: 'admin_roomAvailability_category')]
    public function categoryAvailability(RoomAvailabilityRepository $repo, RoomCategoryRepository $catRepo, string $categoryId): Response
    {
        $category = $catRepo->find($categoryId);
        $availabilityList = $repo->findByRoomCategory($categoryId);

        return $this->render('admin/room_availability/category_view.html.twig', [
            'categoryData' => $category,
            'availabilityList' => $availabilityList,
        ]);
    }

    #[Route('/admin/roomAvailabilityTEST/', name: 'admin_roomAvailability_test')]
    public function availabilityTest(EntityManagerInterface $entityManager): Response
    {
        $avl = new RoomAvailability();

        $avl->setDay(new \DateTime("2024-04-24"));
        $avl->setRoomCategory($entityManager->find(RoomCategory::class, 4));
        $avl->setNumAvailable(2);

        $entityManager->persist($avl);
        $entityManager->flush();

        return $this->json($avl);
    }
}
