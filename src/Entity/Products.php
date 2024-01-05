<?php

namespace App\Entity;

use App\Repository\ProductsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductsRepository::class)]
class Products
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?categories $category_id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'product_id', targetEntity: StockHistoric::class)]
    private Collection $stockHistorics;

    public function __construct()
    {
        $this->stockHistorics = new ArrayCollection();
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

    public function getCategoryId(): ?categories
    {
        return $this->category_id;
    }

    public function setCategoryId(?categories $category_id): static
    {
        $this->category_id = $category_id;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, StockHistoric>
     */
    public function getStockHistorics(): Collection
    {
        return $this->stockHistorics;
    }

    public function addStockHistorics(StockHistoric $stockHistorics): static
    {
        if (!$this->stockHistorics->contains($stockHistorics)) {
            $this->stockHistorics->add($stockHistorics);
            $stockHistorics->setProductId($this);
        }

        return $this;
    }

    public function removeStockHistorics(StockHistoric $stockHistorics): static
    {
        if ($this->stockHistorics->removeElement($stockHistorics)) {
            // set the owning side to null (unless already changed)
            if ($stockHistorics->getProductId() === $this) {
                $stockHistorics->setProductId(null);
            }
        }

        return $this;
    }
}
