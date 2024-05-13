<?php

namespace App\Entity;

use App\Repository\RoomAvailabilityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomAvailabilityRepository::class)]
class RoomAvailability
{
    
    #[ORM\Id]
    #[ORM\Column]
    private ?string $day = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: RoomCategory::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?RoomCategory $roomCategory = null;

    #[ORM\Column]
    private ?int $numAvailable = null;

    public function getDay(): ?\DateTimeInterface
    {
        return new \DateTime($this->day);
    }

    public function setDay(\DateTimeInterface $day): static
    {
        $this->day = $day->format("Y-m-d");

        return $this;
    }

    public function getRoomCategory(): ?RoomCategory
    {
        return $this->roomCategory;
    }

    public function setRoomCategory(?RoomCategory $roomCategory): static
    {
        $this->roomCategory = $roomCategory;

        return $this;
    }

    public function getNumAvailable(): ?int
    {
        return $this->numAvailable;
    }

    public function setNumAvailable(int $numAvailable): static
    {
        $this->numAvailable = $numAvailable;

        return $this;
    }

    public function modNumAvailable(int $diffAmount):void {
        $this->numAvailable -= $diffAmount;
    }
}
