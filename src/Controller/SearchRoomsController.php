<?php

namespace App\Controller;

use App\Repository\FareTableRepository;
use App\Repository\RoomAvailabilityRepository;
use App\Repository\RoomCategoryRepository;
use App\Service\PricesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SearchRoomsController extends AbstractController
{
    #[Route('/api/search/availability', name: 'app_search_availability')]
    public function getAvailabilityForPeriod(Request $request, RoomAvailabilityRepository $repoAvailability): Response
    {
        $payload = $request->toArray();
        if (\is_null($payload)) {
            throw new \Exception('Invalid Payload', 400);
        }
        $filter = $payload["filter"];

        $startDate = new \DateTime($filter["startDate"]);
        $endDate = new \DateTime($filter["endDate"]);

        $rs = $repoAvailability->getAvailabilityForPeriod($startDate, $endDate);

        return $this->json($rs);
    }

    #[Route('/api/search/availabilityDetails', name: 'app_search_availabilityDetails')]
    public function getAvailabilityForPeriodDetails(Request $request, RoomAvailabilityRepository $repoAvailability): Response
    {
        $payload = $request->toArray();
        if (\is_null($payload)) {
            throw new \Exception('Invalid Payload', 400);
        }
        $filter = $payload["filter"];

        $startDate = new \DateTime($filter["startDate"]);
        $endDate = new \DateTime($filter["endDate"]);

        $rs = $repoAvailability->getAvailabilityForPeriodDetails($startDate, $endDate);

        return $this->json($rs);
    }

    #[Route('/api/search/fares', name: 'app_search_faresForCategory')]
    public function getFaresForCategoryOnPeriod(Request $request, RoomCategoryRepository $repoCat, PricesService $priceService): Response
    {
        $payload = $request->toArray();
        if (\is_null($payload)) {
            throw new \Exception('Invalid Payload', 400);
        }
        $filter = $payload["filter"];

        $catId = $filter["roomCategory"];
        $roomCategory = $repoCat->find($catId);
        if ($roomCategory === null) {
            throw new \Exception('Invalid Category', 400);
        }

        $startDate = new \DateTime($filter["startDate"]);
        $endDate = new \DateTime($filter["endDate"]);

        $daysDiff = $startDate->diff($endDate)->days;
        if (!$daysDiff || $startDate > $endDate) {
            throw new \Exception('Invalid time interval', 400);
        }

        $rs = $priceService->getFinalPricesForSearch($roomCategory, $startDate, $endDate);

        return $this->json(["prices" => $rs]);
    }

    #[Route('/search/debug', name: 'app_search_faresDebug')]
    public function faresDebug(Request $request, PricesService $priceService, FareTableRepository $repoFareTable, RoomCategoryRepository $repoCat): Response
    {
        $fareTable[] = $repoFareTable->find(1);
        $fareTable[] = $repoFareTable->find(2);
        $cat = $repoCat->find(4);

        $startDate = new \DateTime("2024-04-27");
        $endDate = new \DateTime("2024-05-02");

        $rs = $priceService->getFinalPricesForSearch($cat, $startDate, $endDate);

        return $this->json($rs);
    }
}
