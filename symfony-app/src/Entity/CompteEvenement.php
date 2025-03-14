<?php

namespace App\Entity;

use App\Repository\CompteEvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompteEvenementRepository::class)]
class CompteEvenement
{
    #[ORM\Id]
    #[ORM\Column(name: "idcompte_evenement", length: 100)]
    private ?string $idcompteEvenement = null;

    #[ORM\Column(length: 150)]
    private ?string $nomCompteEvenement = null;

    #[ORM\OneToMany(mappedBy: 'compteEvenement', targetEntity: FicheTechniqueVoiture::class)]
    private Collection $ficheTechniqueVoitures;

    public function __construct()
    {
        $this->ficheTechniqueVoitures = new ArrayCollection();
    }

    public function getIdcompteEvenement(): ?string
    {
        return $this->idcompteEvenement;
    }

    public function setIdcompteEvenement(string $idcompteEvenement): static
    {
        $this->idcompteEvenement = $idcompteEvenement;

        return $this;
    }

    public function getNomCompteEvenement(): ?string
    {
        return $this->nomCompteEvenement;
    }

    public function setNomCompteEvenement(string $nomCompteEvenement): static
    {
        $this->nomCompteEvenement = $nomCompteEvenement;

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
            $ficheTechniqueVoiture->setCompteEvenement($this);
        }

        return $this;
    }

    public function removeFicheTechniqueVoiture(FicheTechniqueVoiture $ficheTechniqueVoiture): static
    {
        if ($this->ficheTechniqueVoitures->removeElement($ficheTechniqueVoiture)) {
            // set the owning side to null (unless already changed)
            if ($ficheTechniqueVoiture->getCompteEvenement() === $this) {
                $ficheTechniqueVoiture->setCompteEvenement(null);
            }
        }

        return $this;
    }
}
