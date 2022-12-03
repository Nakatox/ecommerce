<?php

namespace App\Model;

use App\Entity\Category;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ProductSearchModel extends AbstractPaginationModel
{
    #[Assert\Type('string')]
    private ?string $name = null;

    #[Assert\Type('Category')]
    private ?Category $category = null;

    #[Assert\Type('boolean')]
    private ?bool $hasQuantity = null;

    #[Assert\Type('integer')]
    #[Assert\PositiveOrZero]
    private ?int $priceMin = null;

    #[Assert\Type('integer')]
    #[Assert\PositiveOrZero]
    private ?int $priceMax = null;


    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }

    public function getHasQuantity(): ?bool
    {
        return $this->hasQuantity;
    }

    public function setHasQuantity(?bool $hasQuantity): void
    {
        $this->hasQuantity = $hasQuantity;
    }

    public function getPriceMin(): ?int
    {
        return $this->priceMin;
    }

    public function setPriceMin(?int $priceMin): void
    {
        $this->priceMin = $priceMin;
    }

    public function getPriceMax(): ?int
    {
        return $this->priceMax;
    }

    public function setPriceMax(?int $priceMax): void
    {
        $this->priceMax = $priceMax;
    }

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if ($this->getPriceMin() > $this->getPriceMax()) {
            $context->buildViolation('This value must be lower than priceMax')
                ->atPath('priceMin')
                ->addViolation();
        }
    }
}
