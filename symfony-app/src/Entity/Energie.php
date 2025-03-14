<?php

namespace App\Entity;

use App\Repository\EnergieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EnergieRepository::class)]
class Energie
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $idenergie = null;

    #[ORM\Column(length: 255)]
    private ?string $nomEnergie = null;

    #[ORM\OneToMany(mappedBy: 'energie', targetEntity: Voiture::class)]
    private Collection $voitures;

    #[ORM\OneToMany(mappedBy: 'energie', targetEntity: FicheTechniqueVoiture::class)]
    private Collection $ficheTechniqueVoitures;

    public function __construct()
    {
        $this->voitures = new ArrayCollection();
        $this->ficheTechniqueVoitures = new ArrayCollection();
    }

    public function getIdenergie(): ?int
    {
        return $this->idenergie;
    }
    
    public function setIdenergie(int $idenergie): static
    {
        $this->idenergie = $idenergie;

        return $this;
    }

    public function getNomEnergie(): ?string
    {
        return $this->nomEnergie;
    }

    public function setNomEnergie(string $nomEnergie): static
    {
        $this->nomEnergie = $nomEnergie;

        return $this;
    }

    /**
     * @return Collection<int, Voiture>
     */
    public function getVoitures(): Collection
    {
        return $this->voitures;
    }

    public function addVoiture(Voiture $voiture): static
    {
        if (!$this->voitures->contains($voiture)) {
            $this->voitures->add($voiture);
            $voiture->setEnergie($this);
        }

        return $this;
    }

    public function removeVoiture(Voiture $voiture): static
    {
        if ($this->voitures->removeElement($voiture)) {
            // set the owning side to null (unless already changed)
            if ($voiture->getEnergie() === $this) {
                $voiture->setEnergie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FicheTechniqueVoiture>
     */
    public function getFicheTechniqueVoitures(): Collection
    {
        return $this->ficheTechniqueVoitures;
    }

    public function addFicheTechniqueVoiture(FicheTechniqueVoiture $ficheTechniqueVoiture): static
    {
        if (!$this->ficheTechniqueVoitures->contains($ficheTechniqueVoiture)) {
            $this->ficheTechniqueVoitures->add($ficheTechniqueVoiture);
            $ficheTechniqueVoiture->setEnergie($this);
        }

        return $this;
    }

    public function removeFicheTechniqueVoiture(FicheTechniqueVoiture $ficheTechniqueVoiture): static
    {
        if ($this->ficheTechniqueVoitures->removeElement($ficheTechniqueVoiture)) {
            // set the owning side to null (unless already changed)
            if ($ficheTechniqueVoiture->getEnergie() === $this) {
                $ficheTechniqueVoiture->setEnergie(null);
            }
        }

        return $this;
    }
}
