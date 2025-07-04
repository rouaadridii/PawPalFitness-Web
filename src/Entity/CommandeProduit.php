<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommandeProduit
 *
 * @ORM\Table(name="commande_produit", indexes={@ORM\Index(name="idProduit", columns={"idProduit"}), @ORM\Index(name="idCommande", columns={"idCommande"})})
 * @ORM\Entity
 */
class CommandeProduit
{
    /**
     * @var \Commande
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Commande")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idCommande", referencedColumnName="idC")
     * })
     */
    private $idcommande;

    /**
     * @var \Produit
     *
     * @ORM\ManyToOne(targetEntity="Produit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idProduit", referencedColumnName="idP")
     * })
     */
    private $idproduit;

    public function getIdcommande(): ?Commande
    {
        return $this->idcommande;
    }

    public function setIdcommande(?Commande $idcommande): static
    {
        $this->idcommande = $idcommande;

        return $this;
    }

    public function getIdproduit(): ?Produit
    {
        return $this->idproduit;
    }

    public function setIdproduit(?Produit $idproduit): static
    {
        $this->idproduit = $idproduit;

        return $this;
    }


}
