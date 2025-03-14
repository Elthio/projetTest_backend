<?php

namespace App\Entity;

use App\Repository\TypeProspectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeProspectRepository::class)]
class TypeProspect
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "idTypeProspect")]
    private ?int $idTypeProspect = null;

    #[ORM\Column(length: 100)]
    private ?string $nomTypeProspect = null;

    #[ORM\OneToMany(mappedBy: 'typeProspect', targetEntity: Proprio::class)]
    private Collection $proprios;

    public function __construct()
    {
        $this->proprios = new ArrayCollection();
    }

    public function getIdTypeProspect(): ?int
    {
        return $this->idTypeProspect;
    }

    public function getNomTypeProspect(): ?string
    {
        return $this->nomTypeProspect;
    }

    public function setNomTypeProspect(string $nomTypeProspect): static
    {
        $this->nomTypeProspect = $nomTypeProspect;

        return $this;
    }

    /**
     * @return Collection<int, Proprio>
     */
    public function getProprios(): Collection
    {
        return $this->proprios;
    }

    public function addProprio(Proprio $proprio): static
    {
        if (!$this->proprios->contains($proprio)) {
            $this->proprios->add($proprio);
            $proprio->setTypeProspect($this);
        }

        return $this;
    }

    public function removeProprio(Proprio $proprio): static
    {
        if ($this->proprios->removeElement($proprio)) {
            // set the owning side to null (unless already changed)
            if ($proprio->getTypeProspect() === $this) {
                $proprio->setTypeProspect(null);
            }
        }

        return $this;
    }
} 