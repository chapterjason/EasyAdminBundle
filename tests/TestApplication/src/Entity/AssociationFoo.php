<?php

namespace EasyCorp\Bundle\EasyAdminBundle\Tests\TestApplication\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`association_foo`')]
class AssociationFoo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\ManyToMany(targetEntity: AssociationBar::class, inversedBy: 'associationFoos')]
    private $associationBars;

    public function __construct()
    {
        $this->associationBars = new ArrayCollection();
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
     * @return Collection|AssociationBar[]
     */
    public function getAssociationBars(): Collection
    {
        return $this->associationBars;
    }

    public function addAssociationBar(AssociationBar $associationBar): self
    {
        if (!$this->associationBars->contains($associationBar)) {
            $this->associationBars[] = $associationBar;
        }

        return $this;
    }

    public function removeAssociationBar(AssociationBar $associationBar): self
    {
        $this->associationBars->removeElement($associationBar);

        return $this;
    }
}
