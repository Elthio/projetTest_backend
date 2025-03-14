<?php

namespace App\Entity;

use App\Repository\LibelleCiviliteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LibelleCiviliteRepository::class)]
class LibelleCivilite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "idLibelleCivilite")]
    private ?int $idLibelleCivilite = null;

    #[ORM\Column(length: 255)]
    private ?string $nomLibelleCivilite = null;

    #[ORM\OneToMany(mappedBy: 'libelleCivilite', targetEntity: Proprio::class)]
    private Collection $proprios;

    public function __construct()
    {
        $this->proprios = new ArrayCollection();
    }

    public function getIdLibelleCivilite(): ?int
    {
        return $this->idLibelleCivilite;
    }

    public function getNomLibelleCivilite(): ?string
    {
        return $this->nomLibelleCivilite;
    }

    public function setNomLibelleCivilite(string $nomLibelleCivilite): static
    {
        $this->nomLibelleCivilite = $nomLibelleCivilite;

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
            $proprio->setLibelleCivilite($this);
        }

        return $this;
    }

    public function removeProprio(Proprio $proprio): static
    {
        if ($this->proprios->removeElement($proprio)) {
            // set the owning side to null (unless already changed)
            if ($proprio->getLibelleCivilite() === $this) {
                $proprio->setLibelleCivilite(null);
            }
        }

        return $this;
    }
}
