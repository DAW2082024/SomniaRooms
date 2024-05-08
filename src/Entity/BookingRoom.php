<?php

namespace App\Entity;

use App\Repository\BookingRoomRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: BookingRoomRepository::class)]
class BookingRoom
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'bookingRooms')]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    #[Ignore]
    private ?Booking $Booking = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Ignore]
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

    #[Ignore]
    public function getBooking(): ?Booking
    {
        return $this->Booking;
    }

    #[Ignore]
    public function setBooking(?Booking $Booking): static
    {
        $this->Booking = $Booking;

        return $this;
    }

    #[Ignore]
    public function getRoomCategory(): ?RoomCategory
    {
        return $this->RoomCategory;
    }

    #[Ignore]
    public function setRoomCategory(?RoomCategory $RoomCategory): static
    {
        $this->RoomCategory = $RoomCategory;

        return $this;
    }

    public function getRoomCategoryId(): int
    {
        return $this->getRoomCategory()->getId();
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
