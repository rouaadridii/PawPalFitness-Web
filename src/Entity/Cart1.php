<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;



/**
 * Cart1
 *
 * @ORM\Table(name="cart", indexes={@ORM\Index(name="nom", columns={"productid"})})
 * @ORM\Entity
 */
class Cart1
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer", nullable=false)
     */
    private $quantity;

    /**
     * @var \Products
     *
     * @ORM\ManyToOne(targetEntity="Products")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id", referencedColumnName="nom")
     * })
     */
    private $productid;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getProductid(): ?Products
    {
        return $this->productid;
    }

    public function setProductid(?Products $productid): self
    {
        $this->productid = $productid;

        return $this;
    }


}
