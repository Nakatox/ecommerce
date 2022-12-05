<?php

namespace App\Entity;

use App\Repository\OrderEntryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OrderEntryRepository::class)]
class OrderEntry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['orderEntry'])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups(['orderEntry'])]
    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[Groups(['orderEntry'])]
    #[ORM\Column]
    private ?int $price = null;

    #[Groups(['orderEntry'])]
    #[ORM\Column(length: 255)]
    private ?string $category = null;

    #[Groups(['orderEntry_order'])]
    #[ORM\ManyToOne(inversedBy: 'orderEntry')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $orderRelate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getOrderRelate(): ?Order
    {
        return $this->orderRelate;
    }

    public function setOrderRelate(?Order $orderRelate): self
    {
        $this->orderRelate = $orderRelate;

        return $this;
    }
}
