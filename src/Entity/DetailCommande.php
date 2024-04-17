<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use App\Repository\DetailCommandeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DetailCommandeRepository::class)]
class DetailCommande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'integer')]
    private $quantity;

    #[ORM\Column(length: 255)]
    private ?float $price = 0.0;

    #[ORM\ManyToOne(inversedBy: 'detailsCmd')]
    private ?Order $order_id = null;

    #[ORM\ManyToOne(inversedBy: 'OrderDetail')]
    private ?Food $food = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getOrderId(): ?Order
    {
        return $this->order_id;
    }

    public function setOrderId(?Order $order_id): static
    {
        $this->order_id = $order_id;

        return $this;
    }

    public function getFood(): ?Food
    {
        return $this->food;
    }

    public function setFood(?Food $food): static
    {
        $this->food = $food;

        return $this;
    }
}
