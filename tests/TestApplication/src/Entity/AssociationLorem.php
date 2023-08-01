<?php

namespace EasyCorp\Bundle\EasyAdminBundle\Tests\TestApplication\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`association_lorem`')]
class AssociationLorem implements \Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToOne(targetEntity: AssociationIpsum::class, inversedBy: 'associationLorem')]
    private $associationIpsum;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAssociationIpsum()
    {
        return $this->associationIpsum;
    }

    public function setAssociationIpsum(?AssociationIpsum $associationIpsum)
    {
        $this->associationIpsum = $associationIpsum;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
