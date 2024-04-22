<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RoomCategoryRepository;
use App\Entity\RoomCategory;
use App\Entity\RoomCategoryDetails;
use App\Entity\RoomCategoryPhoto;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;

class RoomCategoryController extends AbstractController
{

    #[Route('/room/category', name: 'app_room_category')]
    public function getAll(RoomCategoryRepository $catRepo, SerializerInterface $serializer): Response
    {
        $catList = $catRepo->findAll();

        return $this->json(["results" => $catList]);
    }

    #[Route('/room/category/{id}', name: 'app_room_category')]
    public function getById(RoomCategory $category, SerializerInterface $serializer): Response
    {
        return $this->json($category);
    }

}
