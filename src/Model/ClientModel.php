<?php

namespace App\Model;

use App\Entity\Address;
use App\Entity\Category;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Doctrine\Common\Collections\Collection;


class ClientModel
{
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    private ?string $lastName = null;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    private ?string $firstName = null;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    private ?string $email = null;

    #[Assert\NotBlank]
    #[Assert\Type('datetime')]
    private ?\DateTimeInterface $birthDate = null;

    #[Assert\NotBlank]
    #[Assert\Type('collection')]
    private Collection $addresses;

}
