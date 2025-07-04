<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FavorisRepository;

#[ORM\Entity(repositoryClass:FavorisRepository::class)]
class Favoris
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idf=null;

    #[ORM\Column(length: 100)]
    private ?string $noma=null;

    #[ORM\ManyToOne(targetEntity: Animal::class)]
    #[ORM\JoinColumn(name: "IDA", referencedColumnName: "ida")]
    private Animal $ida;

    public function getIdf(): ?int

    {
        return $this->idf;
    }

    public function getNoma(): ?string
    {
        return $this->noma;
    }

    public function setNoma(string $noma): static
    {
        $this->noma = $noma;

        return $this;
    }

    public function getIda(): ?Animal
    {
        return $this->ida;
    }

    public function setIda(?Animal $ida): static
    {
        $this->ida = $ida;

        return $this;
    }

}

