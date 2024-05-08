<?php

namespace App\Entity;

use App\Repository\BookingRoomRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookingRoomRepository::class)]
class BookingRoom
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'bookingRooms')]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Booking $Booking = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?RoomCategory $RoomCategory = null;

    #[ORM\Column]
    private ?int $GuestNumber = null;

    #[ORM\Column]
    private ?int $RoomPrice = null;

    #[ORM\Column]
    private ?int $Amount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooking(): ?Booking
    {
        return $this->Booking;
    }

    public function setBooking(?Booking $Booking): static
    {
        $this->Booking = $Booking;

        return $this;
    }

    public function getRoomCategory(): ?RoomCategory
    {
        return $this->RoomCategory;
    }

    public function setRoomCategory(?RoomCategory $RoomCategory): static
    {
        $this->RoomCategory = $RoomCategory;

        return $this;
    }

    public function getGuestNumber(): ?int
    {
        return $this->GuestNumber;
    }

    public function setGuestNumber(int $GuestNumber): static
    {
        $this->GuestNumber = $GuestNumber;

        return $this;
    }

    public function getRoomPrice(): ?int
    {
        return $this->RoomPrice;
    }

    public function setRoomPrice(int $RoomPrice): static
    {
        $this->RoomPrice = $RoomPrice;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->Amount;
    }

    public function setAmount(int $Amount): static
    {
        $this->Amount = $Amount;

        return $this;
    }

    function __toString(): string
    {
        return $this->getAmount() . " " . $this->getRoomCategory()->getName() . " - " . $this->getGuestNumber() . " Guests";
    }
}
