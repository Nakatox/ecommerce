<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['order'])]
    private ?string $number = null;

    #[ORM\Column]
    #[Groups(['order'])]
    private ?int $totalAmount = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['order_client'])]
    private ?Client $client = null;

    #[ORM\Column(length: 255)]
    #[Groups(['order'])]
    private ?string $addressFacturation = null;

    #[ORM\Column(length: 255)]
    #[Groups(['order'])]
    private ?string $addressDelivery = null;

    #[ORM\OneToMany(mappedBy: 'orderRelate', targetEntity: OrderEntry::class, orphanRemoval: true)]
    #[Groups(['order_orderEntry'])]
    private Collection $orderEntry;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['order'])]
    private ?\DateTimeInterface $created_at = null;

    public function __construct()
    {
        $this->orderEntry = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getTotalAmount(): ?int
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(int $totalAmount): self
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getAddressFacturation(): ?string
    {
        return $this->addressFacturation;
    }

    public function setAddressFacturation(string $addressFacturation): self
    {
        $this->addressFacturation = $addressFacturation;

        return $this;
    }

    public function getAddressDelivery(): ?string
    {
        return $this->addressDelivery;
    }

    public function setAddressDelivery(string $addressDelivery): self
    {
        $this->addressDelivery = $addressDelivery;

        return $this;
    }

    /**
     * @return Collection<int, OrderEntry>
     */
    public function getOrderEntry(): Collection
    {
        return $this->orderEntry;
    }

    public function addOrderEntry(OrderEntry $orderEntry): self
    {
        if (!$this->orderEntry->contains($orderEntry)) {
            $this->orderEntry->add($orderEntry);
            $orderEntry->setOrderRelate($this);
        }

        return $this;
    }

    public function removeOrderEntry(OrderEntry $orderEntry): self
    {
        if ($this->orderEntry->removeElement($orderEntry)) {
            // set the owning side to null (unless already changed)
            if ($orderEntry->getOrderRelate() === $this) {
                $orderEntry->setOrderRelate(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}
