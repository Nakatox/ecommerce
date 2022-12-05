<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['address'])]
    #[ORM\Column(length: 255)]
    private ?string $street = null;

    #[Groups(['address'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $postalCode = null;

    #[Groups(['address'])]
    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[Groups(['address_client'])]
    #[ORM\ManyToOne(inversedBy: 'addresses')]
    private ?Client $client = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

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
}
