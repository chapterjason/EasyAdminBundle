<?php

namespace EasyCorp\Bundle\EasyAdminBundle\Tests\TestApplication\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`association_ipsum`')]
class AssociationIpsum implements \Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToOne(targetEntity: AssociationLorem::class, mappedBy: 'associationIpsum')]
    #[ORM\JoinColumn(name: 'association_lorem_id', referencedColumnName: 'id', nullable: true)]
    private $associationLorem;

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

    public function getAssociationLorem()
    {
        return $this->associationLorem;
    }

    public function setAssociationLorem(?AssociationLorem $associationLorem)
    {
        $this->associationLorem = $associationLorem;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
