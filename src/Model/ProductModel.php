<?php

namespace App\Model;

use App\Entity\Category;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ProductModel
{
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    private ?string $name = null;

    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    #[Assert\PositiveOrZero]
    private ?int $quantity = null;

    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    private ?int $price = null;

    #[Assert\NotBlank]
    #[Assert\Type('float')]
    private ?float $vat = null;

    #[Assert\Type('string')]
    private ?string $short_description = null;

    #[Assert\Type('string')]
    private ?string $description = null;

    #[Assert\NotBlank]
    private ?Category $category = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): void
    {
        $this->price = $price;
    }

    public function getVat(): ?float
    {
        return $this->vat;
    }

    public function setVat(?float $vat): void
    {
        $this->vat = $vat;
    }

    public function getShortDescription(): ?string
    {
        return $this->short_description;
    }

    public function setShortDescription(?string $short_description): void
    {
        $this->short_description = $short_description;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if (in_array($this->vat, [5.5, 10, 21])) {
            $context->buildViolation('This value should not be equal to [5.5, 10, 21]')
                ->atPath('vat')
                ->addViolation();
        }
    }
}
