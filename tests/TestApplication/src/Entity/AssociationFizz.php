<?php

namespace EasyCorp\Bundle\EasyAdminBundle\Tests\TestApplication\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`association_fizz`')]
class AssociationFizz
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\ManyToOne(targetEntity: AssociationBaz::class, inversedBy: 'associationFizzs')]
    #[ORM\JoinColumn(nullable: false)]
    private $associationBaz;

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

    public function getAssociationBaz()
    {
        return $this->associationBaz;
    }

    public function setAssociationBaz(?AssociationBaz $associationBaz)
    {
        $this->associationBaz = $associationBaz;

        return $this;
    }
}
