<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\SalleDeSportRepository;


#[ORM\Entity(repositoryClass:SalleDeSportRepository::class)]
class SalleDeSport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idSalle=null;

    #[ORM\Column(length: 150)]
    private ?string $nomSalle=null;

    #[ORM\Column(length: 150)]
    private ?string $descriptionSalle=null;

    #[ORM\Column(length: 150)]
    private ?string  $regionSalle=null;

    #[ORM\Column(length: 150)]
    private ?string  $imageSalle=null;

    #[ORM\Column(length: 150)]
    private ?string $adresseSalle=null;

    public function getIdSalle(): ?int
    {
        return $this->idSalle;
    }

    public function getNomSalle(): ?string
    {
        return $this->nomSalle;
    }

    public function setNomSalle(string $nomSalle): static
    {
        $this->nomSalle = $nomSalle;

        return $this;
    }

    public function getDescriptionSalle(): ?string
    {
        return $this->descriptionSalle;
    }

    public function setDescriptionSalle(string $descriptionSalle): static
    {
        $this->descriptionSalle = $descriptionSalle;

        return $this;
    }

    public function getRegionSalle(): ?string
    {
        return $this->regionSalle;
    }

    public function setRegionSalle(string $regionSalle): static
    {
        $this->regionSalle = $regionSalle;

        return $this;
    }

    public function getImageSalle(): ?string
    {
        return $this->imageSalle;
    }

    public function setImageSalle(string $imageSalle): static
    {
        $this->imageSalle = $imageSalle;

        return $this;
    }

    public function getAdresseSalle(): ?string
    {
        return $this->adresseSalle;
    }

    public function setAdresseSalle(string $adresseSalle): static
    {
        $this->adresseSalle = $adresseSalle;

        return $this;
    }


}
