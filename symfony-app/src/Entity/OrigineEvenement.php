<?php

namespace App\Entity;

use App\Repository\OrigineEvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrigineEvenementRepository::class)]
#[ORM\Table(name: "OrigineEvenement")]
class OrigineEvenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "idOrigineEvenement")]
    private ?int $idOrigineEvenement = null;

    #[ORM\Column(length: 150)]
    private ?string $nomOrigineEvenement = null;

    #[ORM\OneToMany(mappedBy: 'origineEvenement', targetEntity: FicheTechniqueVoiture::class)]
    private Collection $ficheTechniqueVoitures;

    public function __construct()
    {
        $this->ficheTechniqueVoitures = new ArrayCollection();
    }

    public function getIdOrigineEvenement(): ?int
    {
        return $this->idOrigineEvenement;
    }
    
    public function setIdOrigineEvenement(int $idOrigineEvenement): static
    {
        $this->idOrigineEvenement = $idOrigineEvenement;

        return $this;
    }

    public function getNomOrigineEvenement(): ?string
    {
        return $this->nomOrigineEvenement;
    }

    public function setNomOrigineEvenement(string $nomOrigineEvenement): static
    {
        $this->nomOrigineEvenement = $nomOrigineEvenement;

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
            $ficheTechniqueVoiture->setOrigineEvenement($this);
        }

        return $this;
    }

    public function removeFicheTechniqueVoiture(FicheTechniqueVoiture $ficheTechniqueVoiture): static
    {
        if ($this->ficheTechniqueVoitures->removeElement($ficheTechniqueVoiture)) {
            // set the owning side to null (unless already changed)
            if ($ficheTechniqueVoiture->getOrigineEvenement() === $this) {
                $ficheTechniqueVoiture->setOrigineEvenement(null);
            }
        }

        return $this;
    }
}
