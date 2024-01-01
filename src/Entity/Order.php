<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type:'datetime')]
    private ?\DateTimeInterface $date_placed;

    #[ORM\Column(length: 255, options: ['default' => 'pending'])]
    private ?string $status = 'pending';

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'order_id', targetEntity: DetailCommande::class, cascade:['persist'])]
    private Collection $detailsCmd;

    // #[ORM\ManyToMany(targetEntity: Food::class, inversedBy: 'orders')]
    // #[ORM\ManyToMany(targetEntity: Food::class, inversedBy: 'orders')]
    // #[ORM\JoinTable(name: 'order_food')]
    // private Collection $food;

    public function __construct()
    {
        $this->date_placed = new \DateTime();
        $this->detailsCmd = new ArrayCollection();
        // $this->food = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatePlaced(): ?\DateTimeInterface
    {
        return $this->date_placed;
    }

    public function setDatePlaced(\DateTimeInterface $date_placed): self
    {
        $this->date_placed = $date_placed;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, DetailCommande>
     */
    public function getDetailsCmd(): Collection
    {
        return $this->detailsCmd;
    }

    public function addDetailsCmd(DetailCommande $detailsCmd): static
    {
        if (!$this->detailsCmd->contains($detailsCmd)) {
            $this->detailsCmd->add($detailsCmd);
            $detailsCmd->setOrderId($this);
        }

        return $this;
    }

    public function removeDetailsCmd(DetailCommande $detailsCmd): static
    {
        if ($this->detailsCmd->removeElement($detailsCmd)) {
            // set the owning side to null (unless already changed)
            if ($detailsCmd->getOrderId() === $this) {
                $detailsCmd->setOrderId(null);
            }
        }

        return $this;
    }

    public function __toString(): string {
        return $this->id;
    }
    
}
