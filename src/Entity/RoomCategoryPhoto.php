<?php

namespace App\Entity;

use App\Repository\RoomCategoryPhotoRepository;
use Symfony\Component\Serializer\Annotation\Ignore;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomCategoryPhotoRepository::class)]
class RoomCategoryPhoto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $altText = null;

    #[ORM\Column(length: 30)]
    private ?string $kind = null;

    #[ORM\ManyToOne(inversedBy: 'roomCategoryPhotos')]
    #[ORM\JoinColumn(nullable: false)]
    #[Ignore]
    private ?RoomCategory $roomCategory = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function getAltText(): ?string
    {
        return $this->altText;
    }

    public function setAltText(?string $altText): static
    {
        $this->altText = $altText;

        return $this;
    }

    public function getKind(): ?string
    {
        return $this->kind;
    }

    public function setKind(string $kind): static
    {
        $this->kind = $kind;

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
