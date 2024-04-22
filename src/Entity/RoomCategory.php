<?php

namespace App\Entity;

use App\Repository\RoomCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Ignore;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomCategoryRepository::class)]
class RoomCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $bedType = null;

    #[ORM\Column]
    private ?int $maxGuestNum = null;

    /**
     * @var Collection<int, RoomCategoryPhoto>
     */
    #[ORM\OneToMany(targetEntity: RoomCategoryPhoto::class, mappedBy: 'roomCategory', orphanRemoval: true)]
    private Collection $roomCategoryPhotos;

    /**
     * @var Collection<int, RoomCategoryDetails>
     */
    #[ORM\OneToMany(targetEntity: RoomCategoryDetails::class, mappedBy: 'roomCategory', orphanRemoval: true)]
    private Collection $roomCategoryDetails;

    public function __construct()
    {
        $this->roomCategoryPhotos = new ArrayCollection();
        $this->roomCategoryDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getBedType(): ?string
    {
        return $this->bedType;
    }

    public function setBedType(?string $bedType): static
    {
        $this->bedType = $bedType;

        return $this;
    }

    public function getMaxGuestNum(): ?int
    {
        return $this->maxGuestNum;
    }

    public function setMaxGuestNum(int $maxGuestNum): static
    {
        $this->maxGuestNum = $maxGuestNum;

        return $this;
    }

    /**
     * @return Collection<int, RoomCategoryPhoto>
     */
    public function getRoomCategoryPhotos(): Collection
    {
        return $this->roomCategoryPhotos;
    }

    public function addRoomCategoryPhoto(RoomCategoryPhoto $roomCategoryPhoto): static
    {
        if (!$this->roomCategoryPhotos->contains($roomCategoryPhoto)) {
            $this->roomCategoryPhotos->add($roomCategoryPhoto);
            $roomCategoryPhoto->setRoomCategory($this);
        }

        return $this;
    }

    public function removeRoomCategoryPhoto(RoomCategoryPhoto $roomCategoryPhoto): static
    {
        if ($this->roomCategoryPhotos->removeElement($roomCategoryPhoto)) {
            // set the owning side to null (unless already changed)
            if ($roomCategoryPhoto->getRoomCategory() === $this) {
                $roomCategoryPhoto->setRoomCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RoomCategoryDetails>
     */
    public function getRoomCategoryDetails(): Collection
    {
        return $this->roomCategoryDetails;
    }

    public function addRoomCategoryDetail(RoomCategoryDetails $roomCategoryDetail): static
    {
        if (!$this->roomCategoryDetails->contains($roomCategoryDetail)) {
            $this->roomCategoryDetails->add($roomCategoryDetail);
            $roomCategoryDetail->setRoomCategory($this);
        }

        return $this;
    }

    public function removeRoomCategoryDetail(RoomCategoryDetails $roomCategoryDetail): static
    {
        if ($this->roomCategoryDetails->removeElement($roomCategoryDetail)) {
            // set the owning side to null (unless already changed)
            if ($roomCategoryDetail->getRoomCategory() === $this) {
                $roomCategoryDetail->setRoomCategory(null);
            }
        }

        return $this;
    }
}
