<?php

namespace App\Entity;

use App\Repository\TypeVentesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeVentesRepository::class)]
class TypeVentes
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $idTypesVentes = null;

    #[ORM\Column(length: 255)]
    private ?string $nomTypeVente = null;

    #[ORM\OneToMany(mappedBy: 'typeVente', targetEntity: FicheVente::class)]
    private Collection $ficheVentes;

    #[ORM\OneToMany(mappedBy: 'typeVente', targetEntity: FicheTechniqueVoiture::class)]
    private Collection $ficheTechniqueVoitures;

    public function __construct()
    {
        $this->ficheVentes = new ArrayCollection();
        $this->ficheTechniqueVoitures = new ArrayCollection();
    }

    public function getIdTypesVentes(): ?int
    {
        return $this->idTypesVentes;
    }
    
    public function setIdTypesVentes(int $idTypesVentes): static
    {
        $this->idTypesVentes = $idTypesVentes;

        return $this;
    }

    public function getNomTypeVente(): ?string
    {
        return $this->nomTypeVente;
    }

    public function setNomTypeVente(string $nomTypeVente): static
    {
        $this->nomTypeVente = $nomTypeVente;

        return $this;
    }

    /**
     * @return Collection<int, FicheVente>
     */
    public function getFicheVentes(): Collection
    {
        return $this->ficheVentes;
    }

    public function addFicheVente(FicheVente $ficheVente): static
    {
        if (!$this->ficheVentes->contains($ficheVente)) {
            $this->ficheVentes->add($ficheVente);
            $ficheVente->setTypeVente($this);
        }

        return $this;
    }

    public function removeFicheVente(FicheVente $ficheVente): static
    {
        if ($this->ficheVentes->removeElement($ficheVente)) {
            // set the owning side to null (unless already changed)
            if ($ficheVente->getTypeVente() === $this) {
                $ficheVente->setTypeVente(null);
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
            $ficheTechniqueVoiture->setTypeVente($this);
        }

        return $this;
    }

    public function removeFicheTechniqueVoiture(FicheTechniqueVoiture $ficheTechniqueVoiture): static
    {
        if ($this->ficheTechniqueVoitures->removeElement($ficheTechniqueVoiture)) {
            // set the owning side to null (unless already changed)
            if ($ficheTechniqueVoiture->getTypeVente() === $this) {
                $ficheTechniqueVoiture->setTypeVente(null);
            }
        }

        return $this;
    }
}
