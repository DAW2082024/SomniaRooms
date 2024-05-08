<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\BookingCustomer;
use App\Entity\BookingRoom;
use App\Repository\BookingRepository;
use App\Repository\RoomCategoryRepository;
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
    public function newBooking(Request $request, BookingRepository $repoBooking, EntityManagerInterface $entityManager, RoomCategoryRepository $repoCat): JsonResponse
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
            //$booking = new Booking();
            $booking->setBookingTime($currentTime);
            $booking->setArrivalDate($arrivalDate);
            $booking->setDepartureDate($departureDate);
            $booking->setRefNumber($currentTime->format("Y") . "001");

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

            foreach ($reqRoomList as $roomItem) {
                $room = new BookingRoom();
                $room->setBooking($booking);

                //Get Room category.
                $roomCategory = $repoCat->find($roomItem["roomCategory"]);
                if($roomCategory == null) {
                    throw new HttpException(400, "La RoomCategory no es válida.");
                }

                $room->setRoomCategory($roomCategory);
                $room->setGuestNumber($roomItem["guestNumber"]);
                $room->setAmount($roomItem["amount"]);
                $room->setRoomPrice(5000);

                $entityManager->persist($room);
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
