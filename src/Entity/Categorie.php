<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategorieRepository;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass:CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idc=null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Vous devez mettre le nom de votre animal!!!")]
    private ?string $nomc=null;

    public function getIdc(): ?int
    {
        return $this->idc;
    }

    public function getNomc(): ?string
    {
        return $this->nomc;
    }

    public function setNomc(string $nomc): static
    {
        $this->nomc = $nomc;

        return $this;
    }

}
