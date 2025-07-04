<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AnimalRepository;

use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass:AnimalRepository::class)]
class Animal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $ida=null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Vous devez mettre le nom de votre animal!!!")]
    private ?string $nom=null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Vous devez mettre l'age!!!")]
    #[Assert\Type(type: "integer", message: "Âge doit être un nombre entier")]
    #[Assert\Range(min: 1, minMessage: "Âge doit être supérieur à 0")]
    private ?int $age=null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Vous devez mettre le type!!!")]
    private ?string $type=null;

    #[ORM\Column(length: 500)]
    #[Assert\NotBlank(message: "Vous devez mettre les details!!!")]
    private ?string $details=null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Vous devez mettre le poids!!!")]
    #[Assert\Type(type: "float", message: "poids doit être un nombre")]
    #[Assert\Range(
        min: 0.1,
        minMessage: "Le poids doit être supérieur à 0",
    )]
    private ?float $poids=null;

    #[ORM\ManyToOne(targetEntity: Categorie::class)]
    #[ORM\JoinColumn(name: "IDC", referencedColumnName: "idc")]
    private Categorie $idc;

    #[ORM\ManyToOne(targetEntity: Personne::class)]
    #[ORM\JoinColumn(name: "IDU", referencedColumnName: "id")]
    private Personne $idu;


    public function getIda(): ?int
    {
        return $this->ida;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }


    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(string $details): static
    {
        $this->details = $details;

        return $this;
    }

    public function getPoids(): ?float
    {
        return $this->poids;
    }

    public function setPoids(float $poids): static
    {
        $this->poids = $poids;

        return $this;
    }

    public function getIdc(): ?Categorie
    {
        return $this->idc;
    }

    public function setIdc(?Categorie $idc): static
    {
        $this->idc = $idc;

        return $this;
    }

    public function getIdu(): ?Personne
    {
        return $this->idu;
    }

    public function setIdu(?Personne $idu): static
    {
        $this->idu = $idu;

        return $this;
    }

}


