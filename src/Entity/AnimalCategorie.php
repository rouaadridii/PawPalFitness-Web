<?php

namespace App\Entity;

use App\Repository\AnimalCategorieRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnimalCategorieRepository::class)]
class AnimalCategorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $IDC = null;

    #[ORM\ManyToOne(targetEntity: Animal::class)]
    #[ORM\JoinColumn(name: "IDA", referencedColumnName: "IDAC")]
    private Animal $IDAC;
    

    #[ORM\ManyToOne(targetEntity: Personne::class)]
    #[ORM\JoinColumn(name: "id", referencedColumnName: "IDUC")]
    private Personne $IDUC;

    public function getIDC(): ?int
    {
        return $this->IDC;
    }

    public function getIDAC(): ?Animal
    {
        return $this->IDAC;
    }

    public function setIDAC(?Animal $IDA): static
    {
        $this->IDAC = $IDAC;

        return $this;
    }

    public function getIDUC(): ?int
    {
        return $this->IDUC;
    }

    public function setIDUC(int $IDUC): static
    {
        $this->IDUC = $IDUC;

        return $this;
    }
}
