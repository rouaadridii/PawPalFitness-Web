<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\RoleRepository;

#[ORM\Entity(repositoryClass:RoleRepository::class)]
class Role
{
    #[ORM\Id]
    #[ORM\GeneratedValue]

    #[ORM\Column(name: "role_id")]
    private ?int $roleId=null;

    #[ORM\Column(length: 50)]
    private ?string $roleName=null;

    public function getRoleId(): ?string
    {
        return $this->roleId;
    }

    public function getRoleName(): ?string
    {
        return $this->roleName;
    }

    public function setRoleName(string $roleName): static
    {
        $this->roleName = $roleName;

        return $this;
    }


}