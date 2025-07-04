<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Commande
 *
 * @ORM\Table(name="commande", indexes={@ORM\Index(name="idcart", columns={"idcart"})})
 * @ORM\Entity(repositoryClass="App\Repository\CommandeRepository")
 */
class Commande
{
    /**
     * @var int
     *
     * @ORM\Column(name="idc", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"commande"})
     */
    private $idc;



    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=225, nullable=false)
     * @Groups({"commande"})
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=225, nullable=false)
     * @Groups({"commande"})
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=225, nullable=false)
     * @Groups({"commande"})
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="adresseUser", type="string", length=225, nullable=false)
     * @Groups({"commande"})
     */
    private $adresse;

    /**
     * @var int
     *
     * @ORM\Column(name="numTelephone", type="integer", nullable=false)
     * @Groups({"commande"})
     */
    private $numtelephone;

    /**
     * @var string
     *
     * @ORM\Column(name="dateCommande", type="string", nullable=false)
     * @Groups({"commande"})
     */
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(name="prixTotal", type="integer", nullable=false)
     * @Groups({"commande"})
     */
    private $totalcost;

    public function getidc(): ?int
    {
        return $this->idc;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getNumtelephone(): ?int
    {
        return $this->numtelephone;
    }

    public function setNumtelephone(int $numtelephone): self
    {
        $this->numtelephone = $numtelephone;

        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    public function getTotalcost(): ?int
    {
        return $this->totalcost;
    }

    public function setTotalcost(int $totalcost): self
    {
        $this->totalcost = $totalcost;

        return $this;
    }


}
