<?php

namespace EasyCorp\Bundle\EasyAdminBundle\Tests\TestApplication\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`association_baz`')]
class AssociationBaz implements \Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(targetEntity: AssociationFizz::class, mappedBy: 'associationBaz', orphanRemoval: true)]
    private $associationFizzs;

    public function __construct()
    {
        $this->associationFizzs = new ArrayCollection();
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
     * @return Collection|AssociationFizz[]
     */
    public function getAssociationFizzs(): Collection
    {
        return $this->associationFizzs;
    }

    public function addAssociationFizz(AssociationFizz $associationFizz): self
    {
        if (!$this->associationFizzs->contains($associationFizz)) {
            $this->associationFizzs[] = $associationFizz;
            $associationFizz->setAssociationBaz($this);
        }

        return $this;
    }

    public function removeAssociationFizz(AssociationFizz $associationFizz): self
    {
        if ($this->associationFizzs->removeElement($associationFizz)) {
            // set the owning side to null (unless already changed)
            if ($associationFizz->getAssociationBaz() === $this) {
                $associationFizz->setAssociationBaz(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
