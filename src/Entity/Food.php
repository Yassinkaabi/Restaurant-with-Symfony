<?php

namespace App\Entity;

use App\Repository\FoodRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: FoodRepository::class)]
#[Vich\Uploadable]
class Food
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Vich\UploadableField(mapping: 'food_images', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;
    
    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?float $price = 0.0;

    #[ORM\Column]
    private ?bool $is_available = null;

    #[ORM\ManyToOne(inversedBy: 'menuItems')]
    private ?Categorie $menuItem = null;

    #[ORM\OneToMany(mappedBy: 'food', targetEntity: DetailCommande::class)]
    private Collection $OrderDetail;

    // #[ORM\ManyToMany(targetEntity: Order::class, mappedBy: 'food')]
    // private Collection $orders;
    
    public function __construct()
    {
        $this->OrderDetail = new ArrayCollection();
        // $this->orders = new ArrayCollection();
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
    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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

    public function isIsAvailable(): ?bool
    {
        return $this->is_available;
    }

    public function setIsAvailable(bool $is_available): static
    {
        $this->is_available = $is_available;

        return $this;
    }

    public function getMenuItem(): ?Categorie
    {
        return $this->menuItem;
    }

    public function setMenuItem(?Categorie $menuItem): static
    {
        $this->menuItem = $menuItem;

        return $this;
    }

    public function __toString(): string {
        return $this->name;
    }

    /**
     * @return Collection<int, DetailCommande>
     */
    public function getOrderDetail(): Collection
    {
        return $this->OrderDetail;
    }

    public function addOrderDetail(DetailCommande $orderDetail): static
    {
        if (!$this->OrderDetail->contains($orderDetail)) {
            $this->OrderDetail->add($orderDetail);
            $orderDetail->setFood($this);
        }

        return $this;
    }

    public function removeOrderDetail(DetailCommande $orderDetail): static
    {
        if ($this->OrderDetail->removeElement($orderDetail)) {
            // set the owning side to null (unless already changed)
            if ($orderDetail->getFood() === $this) {
                $orderDetail->setFood(null);
            }
        }

        return $this;
    }

}
