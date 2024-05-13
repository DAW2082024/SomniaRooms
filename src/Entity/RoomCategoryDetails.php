<?php

namespace App\Entity;

use App\Repository\RoomCategoryDetailsRepository;
use Symfony\Component\Serializer\Annotation\Ignore;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomCategoryDetailsRepository::class)]
class RoomCategoryDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $detailsSection = null;

    #[ORM\Column(length: 255)]
    private ?string $detailValue = null;

    #[ORM\ManyToOne(inversedBy: 'roomCategoryDetails')]
    #[ORM\JoinColumn(nullable: false)]
    #[Ignore]
    private ?RoomCategory $roomCategory = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDetailsSection(): ?string
    {
        return $this->detailsSection;
    }

    public function setDetailsSection(string $detailsSection): static
    {
        $this->detailsSection = $detailsSection;

        return $this;
    }

    public function getDetailValue(): ?string
    {
        return $this->detailValue;
    }

    public function setDetailValue(string $detailValue): static
    {
        $this->detailValue = $detailValue;

        return $this;
    }

    #[Ignore]
    public function getRoomCategory(): ?RoomCategory
    {
        return $this->roomCategory;
    }

    #[Ignore]
    public function setRoomCategory(?RoomCategory $roomCategory): static
    {
        $this->roomCategory = $roomCategory;

        return $this;
    }
}
