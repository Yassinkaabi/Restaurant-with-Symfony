<?php

namespace App\Entity;

use App\Repository\TableRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TableRepository::class)]
#[ORM\Table(name: '`table`')]
class Table
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $number = null;

    #[ORM\Column(length: 255)]
    private ?string $number_of_places = null;

    #[ORM\Column]
    private ?bool $is_reserved = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $location = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getNumberOfPlaces(): ?string
    {
        return $this->number_of_places;
    }

    public function setNumberOfPlaces(string $number_of_places): static
    {
        $this->number_of_places = $number_of_places;

        return $this;
    }

    public function isIsReserved(): ?bool
    {
        return $this->is_reserved;
    }

    public function setIsReserved(bool $is_reserved): static
    {
        $this->is_reserved = $is_reserved;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function __toString(): string
    {
        return $this->location;
    }
}
