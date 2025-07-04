<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\VerificationCodesRepository;

#[ORM\Entity(repositoryClass:VerificationCodesRepository::class)]
class VerificationCodes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id=null;

    #[ORM\Column(length: 255)]
    private ?string $email=null;

    #[ORM\Column(length: 10)]
    private ?string $code=null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }


}