<?php

namespace App\Controller\Admin;

use App\Entity\RoomAvailability;
use App\Entity\RoomCategory;
use App\Repository\RoomAvailabilityRepository;
use App\Repository\RoomCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("ROLE_ADMIN")]
class RoomAvailabilityAdminController extends AbstractController
{

    private $adminUrlGenerator;
    private EntityManagerInterface $entityManager;

    public function __construct(AdminUrlGenerator $adminUrlGenerator, EntityManagerInterface $em)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
        $this->entityManager = $em;
    }

    #[Route('/admin/roomAvailability', name: 'admin_roomAvailability')]
    public function index(RoomAvailabilityRepository $repo, RoomCategoryRepository $catRepo): Response
    {
        $categoryList = $catRepo->findAll();

        return $this->render('admin/room_availability/index.html.twig', [
            'roomCategoryList' => $categoryList,
        ]);
    }

    // List RoomAvailability items from category.
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

    // Edit a RoomAvailability numAvailable.
    #[Route('/admin/roomAvailability/edit/{categoryId}/{day}', name: 'admin_roomAvailability_category_update')]
    public function updateCategoryAvailability(Request $request, RoomAvailabilityRepository $repo, string $categoryId, string $day): Response
    {
        $availabilityItem = $repo->find(["day" => $day, "roomCategory" => $categoryId]);

        if ($availabilityItem == null) {
            //TODOME: Aquí deberíamos llevar a una página de error o algo.

            $url = $this->adminUrlGenerator->setRoute('admin_roomAvailability_category', [
                'categoryId' => $item->getId(),
            ])->generateUrl();
            return $this->redirect($url);
        }

        $form = $this->createFormBuilder($availabilityItem)
            ->add('numAvailable', NumberType::class, ['label' => 'Num Available'])
            ->add('save', SubmitType::class, ['label' => 'Update value'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$availabilityItem` variable has also been updated
            
            $this->entityManager->flush();

            $url = $this->adminUrlGenerator->setRoute('admin_roomAvailability_category', [
                'categoryId' => $categoryId,
            ])->generateUrl();

            return $this->redirect($url);
        }

        return $this->render('admin/room_availability/availability_day_edit.html.twig', [
            'form' => $form,
            'availabilityData' => $availabilityItem,
        ]);
    }

    // Bulk edit all roomAvailability of a category on period.
    #[Route('/admin/roomAvailability/{categoryId}/bulkedit', name: 'admin_roomAvailability_category_bulkupdate')]
    public function bulkUpdateCategoryAvailability(Request $request, RoomAvailabilityRepository $repo, string $categoryId): Response
    {

        $form = $this->createFormBuilder()
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('numAvailable', NumberType::class, [
                'label' => 'Num Available',
            ])
            ->add('save', SubmitType::class, ['label' => 'Update value'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            $data = $form->getData();

            $categoryItem = $this->entityManager->find(RoomCategory::class, $categoryId);
            $repo->bulkEditRoomAvailabilityForCategoryOnPeriod($categoryItem, $data['startDate'], $data['endDate'], $data['numAvailable']);

            $url = $this->adminUrlGenerator->setRoute('admin_roomAvailability')->generateUrl();

            return $this->redirect($url);
        }

        return $this->render('admin/room_availability/availability_bulk_edit.html.twig', [
            'form' => $form,
            'categoryId' => $categoryId,
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
