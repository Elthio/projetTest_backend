<?php

namespace App\Entity;

use App\Repository\CompteAffaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompteAffaireRepository::class)]
class CompteAffaire
{
    #[ORM\Id]
    #[ORM\Column(name: "idcompte_affaire", length: 100)]
    private ?string $idcompteAffaire = null;

    #[ORM\Column(length: 150)]
    private ?string $nomCompteAffaire = null;

    #[ORM\OneToMany(mappedBy: 'compteAffaire', targetEntity: FicheTechniqueVoiture::class)]
    private Collection $ficheTechniqueVoitures;

    public function __construct()
    {
        $this->ficheTechniqueVoitures = new ArrayCollection();
    }

    public function getIdcompteAffaire(): ?string
    {
        return $this->idcompteAffaire;
    }

    public function setIdcompteAffaire(string $idcompteAffaire): static
    {
        $this->idcompteAffaire = $idcompteAffaire;

        return $this;
    }

    public function getNomCompteAffaire(): ?string
    {
        return $this->nomCompteAffaire;
    }

    public function setNomCompteAffaire(string $nomCompteAffaire): static
    {
        $this->nomCompteAffaire = $nomCompteAffaire;

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
            $ficheTechniqueVoiture->setCompteAffaire($this);
        }

        return $this;
    }

    public function removeFicheTechniqueVoiture(FicheTechniqueVoiture $ficheTechniqueVoiture): static
    {
        if ($this->ficheTechniqueVoitures->removeElement($ficheTechniqueVoiture)) {
            if ($ficheTechniqueVoiture->getCompteAffaire() === $this) {
                $ficheTechniqueVoiture->setCompteAffaire(null);
            }
        }

        return $this;
    }
}
