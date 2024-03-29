<?php

namespace App\Entity;

use App\Repository\MobileRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\SerializerInterface;
use Hateoas\Configuration\Annotation as Hateoas;

#[ORM\Entity(repositoryClass: MobileRepository::class)]

/**
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *       "detailMobile",
 *          parameters = { "id" = "expr(object.getId())" } 
 *          ),
 *        exclusion = @Hateoas\Exclusion(groups="getMobile")
 * )
 * 
 */
class Mobile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $feature = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getFeature(): ?string
    {
        return $this->feature;
    }

    public function setFeature(?string $feature): static
    {
        $this->feature = $feature;

        return $this;
    }
}
