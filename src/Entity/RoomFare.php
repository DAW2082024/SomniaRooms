<?php

namespace App\Entity;

use App\Repository\RoomFareRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomFareRepository::class)]
class RoomFare
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'roomFares')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FareTable $fareTable = null;

    #[ORM\Column]
    private ?int $guestNumber = null;

    #[ORM\Column]
    private ?int $fareAmount = null;

    #[ORM\Column(length: 2, nullable: true)]
    private ?string $dayType = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFareTable(): ?FareTable
    {
        return $this->fareTable;
    }

    public function setFareTable(?FareTable $fareTable): static
    {
        $this->fareTable = $fareTable;

        return $this;
    }

    public function getGuestNumber(): ?int
    {
        return $this->guestNumber;
    }

    public function setGuestNumber(int $guestNumber): static
    {
        $this->guestNumber = $guestNumber;

        return $this;
    }

    public function getFareAmount(): ?int
    {
        return $this->fareAmount;
    }

    public function setFareAmount(int $fareAmount): static
    {
        $this->fareAmount = $fareAmount;

        return $this;
    }

    public function getDayType(): ?string
    {
        return $this->dayType;
    }

    public function setDayType(?string $dayType): static
    {
        $this->dayType = $dayType;

        return $this;
    }
}
