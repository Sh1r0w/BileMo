<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\SerializerInterface;
use Hateoas\Configuration\Annotation as Hateoas;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]

/**
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *       "detailCustomer",
 *          parameters = { "id" = "expr(object.getId())" } 
 *          ),
 *        exclusion = @Hateoas\Exclusion(groups="getCustomers"),
 * )
 * 
 *  * @Hateoas\Relation(
 *      "delete",
 *      href = @Hateoas\Route(
 *       "deleteCustomer",
 *          parameters = { "id" = "expr(object.getId())" } 
 *          ),
 *        exclusion = @Hateoas\Exclusion(groups="getCustomers")
 * )
 * 
 */
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getCustomers"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getCustomers"])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'customers')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["getCustomers"])]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
