<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints\NotBlank;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\PersonneRepository;
use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface;

#[ORM\Entity(repositoryClass: PersonneRepository::class)]
class Personne 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id", type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type:"integer")]
    private $failedLoginAttempts = 0;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Nom est obligatoire")]
    private ?string $nom = null;
    #[ORM\Column(type: "boolean")]
    private bool $isBanned = false;
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Prénom est obligatoire")]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Région est obligatoire")]
    private ?string $region = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: "Email est obligatoire")]
    #[Assert\Email(message: "Email non valide")]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Mot de passe est obligatoire")]
    #[Assert\Length(
        min: 8,
        minMessage: "Le mot de passe doit contenir au moins {{ limit }} caractères."
    )]
    private ?string $password = null;

    #[ORM\Column(type: "integer", nullable: true)]
    #[Assert\NotBlank(message: "Âge est obligatoire")]
    #[Assert\Type(type: "integer", message: "Âge doit être un nombre entier")]
    #[Assert\Range(min: 1, minMessage: "Âge doit être supérieur à 0")]
    private ?int $age = null;
    
    #[ORM\ManyToOne(targetEntity: Role::class)]
    #[ORM\JoinColumn(name: "role_id", referencedColumnName: "role_id")]
    private ?Role $role = null; 

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }
    public function getFailedLoginAttempts(): int
    {
        return $this->failedLoginAttempts;
    }

    public function incrementFailedLoginAttempts(): void
    {
        $this->failedLoginAttempts++;
    }

    public function resetFailedLoginAttempts(): void
    {
        $this->failedLoginAttempts = 0;
    }
    public function getIsBanned(): bool
    {
        return $this->isBanned;
    }
    
    public function setIsBanned(bool $isBanned): self
    {
        $this->isBanned = $isBanned;
        return $this;
    }
    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }
   
    public function getRegion(): ?string
    {
        return $this->region;
    }
    public function checkPassword(string $plainPassword): bool
    {
        // Hash the plain password using the same algorithm and compare it with the stored hashed password
        return hash('sha256', $plainPassword) === $this->password;
    }
    public function setRegion(string $region): self
    {
        $this->region = $region;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = hash('sha256', $password);
        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;
        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;
        return $this;
    }


 
}
