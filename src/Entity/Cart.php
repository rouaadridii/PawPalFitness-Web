<?php

namespace App\Entity;


use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CartRepository;
use DateTime;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $cartId = null;

    #[ORM\ManyToOne(targetEntity: Reservation::class)]
    #[ORM\JoinColumn(name: 'reservation_id', referencedColumnName: 'id')]
    private ?Reservation $reservation;

    #[ORM\Column]
    private ?int $places;

    #[ORM\Column]
    private ?int $quantity;

    #[ORM\Column(type: 'datetime', options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTime $timestamp;

    public function getCartId(): ?int
    {
        return $this->cartId;
    }

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(?Reservation $reservation): static
    {
        $this->reservation = $reservation;

        return $this;
    }

    public function getPlaces(): ?int
    {
        return $this->places;
    }

    public function setPlaces(int $places): static
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }


    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }


    public function getTimestamp(): ?DateTime
    {
        return $this->productid;
    }

    public function setTimestamp(DateTime $timestamp): static
    {
        $this->productid = $productid;

        return $this;
    }


}
