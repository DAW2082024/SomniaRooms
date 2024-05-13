<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookingRepository::class)]
class Booking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $ArrivalDate = null;

    #[ORM\Column(type : Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DepartureDate = null;

    #[ORM\Column(type : Types::DATETIMETZ_MUTABLE)]
    private ?\DateTimeInterface $BookingTime = null;

    #[ORM\OneToOne(mappedBy : 'Booking', cascade: ['persist', 'remove'])]
    private ?BookingCustomer $bookingCustomer = null;

    /**
     * @var Collection<int, BookingRoom>
     */
    #[ORM\OneToMany(targetEntity: BookingRoom::class, mappedBy: 'Booking', orphanRemoval: true, cascade: ['persist'])]
    private Collection $bookingRooms;

    #[ORM\Column(length: 20)]
    private ?string $refNumber = null;

    public function __construct()
    {
        $this->bookingRooms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArrivalDate(): ?\DateTimeInterface
    {
        return $this->ArrivalDate;
    }

    public function setArrivalDate(\DateTimeInterface $ArrivalDate): static
    {
        $this->ArrivalDate = $ArrivalDate;

        return $this;
    }

    public function getDepartureDate(): ?\DateTimeInterface
    {
        return $this->DepartureDate;
    }

    public function setDepartureDate(\DateTimeInterface $DepartureDate): static
    {
        $this->DepartureDate = $DepartureDate;

        return $this;
    }

    public function getBookingTime(): ?\DateTimeInterface
    {
        return $this->BookingTime;
    }

    public function setBookingTime(\DateTimeInterface $BookingTime): static
    {
        $this->BookingTime = $BookingTime;

        return $this;
    }

    public function getBookingCustomer(): ?BookingCustomer
    {
        return $this->bookingCustomer;
    }

    public function setBookingCustomer(BookingCustomer $bookingCustomer): static
    {
        // set the owning side of the relation if necessary
        if ($bookingCustomer->getBooking() !== $this) {
            $bookingCustomer->setBooking($this);
        }

        $this->bookingCustomer = $bookingCustomer;

        return $this;
    }

    /**
     * @return Collection<int, BookingRoom>
     */
    public function getBookingRooms(): Collection
    {
        return $this->bookingRooms;
    }

    public function addBookingRoom(BookingRoom $bookingRoom): static
    {
        if (!$this->bookingRooms->contains($bookingRoom)) {
            $this->bookingRooms->add($bookingRoom);
            $bookingRoom->setBooking($this);
        }

        return $this;
    }

    public function removeBookingRoom(BookingRoom $bookingRoom): static
    {
        if ($this->bookingRooms->removeElement($bookingRoom)) {
            // set the owning side to null (unless already changed)
            if ($bookingRoom->getBooking() === $this) {
                $bookingRoom->setBooking(null);
            }
        }

        return $this;
    }

    public function getRefNumber(): ?string
    {
        return $this->refNumber;
    }

    public function setRefNumber(string $refNumber): static
    {
        $this->refNumber = $refNumber;

        return $this;
    }

    function __toString(): string
    {
        return $this->getRefNumber();
    }
}
