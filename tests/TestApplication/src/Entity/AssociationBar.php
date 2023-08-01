<?php

namespace EasyCorp\Bundle\EasyAdminBundle\Tests\TestApplication\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`association_bar`')]
class AssociationBar
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\ManyToMany(targetEntity: AssociationFoo::class, mappedBy: 'associationBars')]
    private $associationFoos;

    public function __construct()
    {
        $this->associationFoos = new ArrayCollection();
    }

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

    /**
     * @return Collection|AssociationFoo[]
     */
    public function getAssociationFoos(): Collection
    {
        return $this->associationFoos;
    }

    public function addAssociationFoo(AssociationFoo $associationFoo): self
    {
        if (!$this->associationFoos->contains($associationFoo)) {
            $this->associationFoos[] = $associationFoo;
            $associationFoo->addAssociationBar($this);
        }

        return $this;
    }

    public function removeAssociationFoo(AssociationFoo $associationFoo): self
    {
        if ($this->associationFoos->removeElement($associationFoo)) {
            $associationFoo->addAssociationBar($this);
        }

        return $this;
    }
}
