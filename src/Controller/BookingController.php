<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\BookingCustomer;
use App\Entity\BookingRoom;
use App\Repository\BookingRepository;
use App\Repository\RoomCategoryRepository;
use App\Repository\RoomAvailabilityRepository;
use App\Service\PricesService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Attribute\Route;

class BookingController extends AbstractController
{
    #[Route('/api/booking/details', name: 'app_booking_details')]
    public function getBookingDetails(Request $request, BookingRepository $repoBooking): JsonResponse
    {
        $payload = $request->toArray();
        if (\is_null($payload)) {
            throw new \Exception('Invalid Payload', 400);
        }

        $refNumber = $payload["refNumber"];
        $customerEmail = $payload["email"];

        $bookingList = $repoBooking->findBy(["refNumber" => $refNumber]);
        if (\is_null($bookingList) || \count($bookingList) == 0) {
            throw new HttpException(404, 'Invalid Booking');
        }

        $customerBooking = $bookingList[0];
        if ($customerBooking->getBookingCustomer()->getEmail() != $customerEmail) {
            throw new HttpException(404, 'Invalid Booking');
        }

        return $this->json($customerBooking);
    }

    #[Route('/api/booking/create', name: 'app_booking_create')]
    public function newBooking(Request $request, BookingRepository $repoBooking, EntityManagerInterface $entityManager, 
        RoomCategoryRepository $repoCat, RoomAvailabilityRepository $repoAvailability,
        PricesService $priceSrv): JsonResponse
    {
        $payload = $request->toArray();
        if (\is_null($payload)) {
            throw new \Exception('Invalid Payload', 400);
        }


        $booking = new Booking();
        try {
            $reqArrivalDate = $payload["arrivalDate"];
            $reqDepartureDate = $payload["departureDate"];

            $arrivalDate = new \DateTime($reqArrivalDate);
            $departureDate = new \DateTime($reqDepartureDate);

            if ($arrivalDate >= $departureDate) {
                throw new HttpException(400, "Arrival cant be before departure");
            }

            $entityManager->beginTransaction();

            $currentTime = new \DateTime();

            // Create booking from request data.
            $booking->setBookingTime($currentTime);
            $booking->setArrivalDate($arrivalDate);
            $booking->setDepartureDate($departureDate);

            $highest_id = $entityManager->createQueryBuilder()
            ->select('MAX(e.id)')
            ->from('App\Entity\Booking', 'e')
            ->getQuery()
            ->getSingleScalarResult();

            $booking->setRefNumber($currentTime->format("Y") . $highest_id + 1);

            $entityManager->persist($booking);

            // Create booking customer.
            $reqCustomer = $payload["customerDetails"];
            $bookingCustomer = new BookingCustomer();
            $bookingCustomer->setName($reqCustomer["name"]);
            $bookingCustomer->setSurname($reqCustomer["surname"]);
            $bookingCustomer->setEmail($reqCustomer["email"]);
            $bookingCustomer->setPhoneNumber($reqCustomer["phoneNumber"]);
            $bookingCustomer->setBooking($booking);

            $entityManager->persist($bookingCustomer);

            $reqRoomList = $payload["rooms"];
            if (\count($reqRoomList) == 0) {
                throw new HttpException(400, "Se debe indicar al menos una habitación");
            }

            // Create each room.
            foreach ($reqRoomList as $roomItem) {
                $room = new BookingRoom();
                $room->setBooking($booking);

                //Get Room category.
                $roomCategory = $repoCat->find($roomItem["roomCategory"]);
                if ($roomCategory == null) {
                    throw new HttpException(400, "La RoomCategory no es válida.");
                }

                //Check room availability.
                $amountAvailable = $repoAvailability->getAvailabilityForCategoryInPeriod($roomCategory, $arrivalDate, $departureDate);
                $selectedAmount = $roomItem['amount'];
                $selectedGuestAmount = $roomItem["guestNumber"];

                if($amountAvailable == null || $selectedAmount > $amountAvailable) {
                    throw new HttpException(400, "No se pueden reservar más habitaciones de las disponibles.");
                }

                $room->setRoomCategory($roomCategory);
                $room->setGuestNumber($selectedGuestAmount);
                $room->setAmount($selectedAmount);

                //Get Price for period.
                $priceList = $priceSrv->getFinalPricesForSearch($roomCategory, $arrivalDate, $departureDate);
                $priceFound = false;
                foreach ($priceList as $key => $value) {
                    if($key == $selectedGuestAmount) {
                        $room->setRoomPrice($value);
                        $priceFound = true;
                        break;
                    }
                }
                
                if(!$priceFound) {
                    throw new HttpException(500, 
                        "No se puedo obtener el precio para la categoría: " . $roommCategory->getId() . " , guests: " . $selecctedGuestAmount);
                }

                $entityManager->persist($room);

                //If booking is nice, let's update current availability.
                $repoAvailability->updateAvailabilityForRoomCategoryOnPeriod($roomCategory, $arrivalDate, $departureDate, $selectedAmount, true); //Update using "diff" mode.
            }

            $entityManager->commit();
            $entityManager->flush();

        } catch (\Throwable $th) {
            $entityManager->rollback();
            throw $th;
        }

        $results = ["refNumber" => $booking->getRefNumber()];

        return $this->json($results);
    }
}
