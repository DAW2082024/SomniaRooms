<?php

namespace App\Entity;

use App\Repository\FareTableRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FareTableRepository::class)]
class FareTable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?RoomCategory $roomCategory = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type : Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(length : 255, nullable: true)]
    private ?string $comment = null;

    /**
     * @var Collection<int, RoomFare>
     */
    #[ORM\OneToMany(targetEntity: RoomFare::class, mappedBy: 'fareTable', orphanRemoval: true)]
    private Collection $roomFares;

    public function __construct()
    {
        $this->roomFares = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return Collection<int, RoomFare>
     */
    public function getRoomFares(): Collection
    {
        return $this->roomFares;
    }

    public function addRoomFare(RoomFare $roomFare): static
    {
        if (!$this->roomFares->contains($roomFare)) {
            $this->roomFares->add($roomFare);
            $roomFare->setFareTable($this);
        }

        return $this;
    }

    public function removeRoomFare(RoomFare $roomFare): static
    {
        if ($this->roomFares->removeElement($roomFare)) {
            // set the owning side to null (unless already changed)
            if ($roomFare->getFareTable() === $this) {
                $roomFare->setFareTable(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        if (!\is_null($this->comment)) {
            return $this->comment;
        }
        return $this->id;
    }

    /**
     * Get max guest number for current roomFares.
     */
    public function getMaxGuestNumber(): int
    {
        $maxGuests = $this->getRoomFares()->reduce(function (int $acc, RoomFare $value): int {
            return \max($acc, $value->getGuestNumber());
        }, -1);

        return $maxGuests;
    }

    /**
     * Get min guest number for current roomFares.
     */
    public function getMinGuestNumber(): int
    {
        $firstGuestNum = $this->getRoomFares()->first()->getGuestNumber() ?? 999;

        $maxGuests = $this->getRoomFares()->reduce(function (int $acc, RoomFare $value): int {
            return \min($acc, $value->getGuestNumber());
        }, $firstGuestNum);

        return $maxGuests;
    }

    /**
     * Obtiene las tarifas que aplican al GuestNumber indicado.
     * @param int $guestNumber GuestNumber para búsqueda.
     * @return RoomFare[] Lista de tarifas.
     */
    public function getRoomFaresByGuestNumber(int $guestNumber)
    {
        $roomFareList = $this->getRoomFares()->filter(function (RoomFare $element) use ($guestNumber) {
            return $element->getGuestNumber() == $guestNumber;
        });

        return $roomFareList;
    }

    public function isActiveOnDate(\DateTimeInterface $date): bool
    {
        $active = ($this->startDate <= $date && $this->endDate > $date);
        return $active;
    }
}
