<?php

namespace App\Entity;

use App\Repository\TypeVehiculeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeVehiculeRepository::class)]
class TypeVehicule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "idTypeVehicule")]
    private ?int $idTypeVehicule = null;

    #[ORM\Column(length: 255)]
    private ?string $nomTypeVehicule = null;

    #[ORM\OneToMany(mappedBy: 'typeVehicule', targetEntity: FicheVente::class)]
    private Collection $ficheVentes;

    #[ORM\OneToMany(mappedBy: 'typeVehicule', targetEntity: FicheTechniqueVoiture::class)]
    private Collection $ficheTechniqueVoitures;

    public function __construct()
    {
        $this->ficheVentes = new ArrayCollection();
        $this->ficheTechniqueVoitures = new ArrayCollection();
    }

    public function getIdTypeVehicule(): ?int
    {
        return $this->idTypeVehicule;
    }
    
    public function setIdTypeVehicule(int $idTypeVehicule): static
    {
        $this->idTypeVehicule = $idTypeVehicule;

        return $this;
    }

    public function getNomTypeVehicule(): ?string
    {
        return $this->nomTypeVehicule;
    }

    public function setNomTypeVehicule(string $nomTypeVehicule): static
    {
        $this->nomTypeVehicule = $nomTypeVehicule;

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
            $ficheVente->setTypeVehicule($this);
        }

        return $this;
    }

    public function removeFicheVente(FicheVente $ficheVente): static
    {
        if ($this->ficheVentes->removeElement($ficheVente)) {
            // set the owning side to null (unless already changed)
            if ($ficheVente->getTypeVehicule() === $this) {
                $ficheVente->setTypeVehicule(null);
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
            $ficheTechniqueVoiture->setTypeVehicule($this);
        }

        return $this;
    }

    public function removeFicheTechniqueVoiture(FicheTechniqueVoiture $ficheTechniqueVoiture): static
    {
        if ($this->ficheTechniqueVoitures->removeElement($ficheTechniqueVoiture)) {
            // set the owning side to null (unless already changed)
            if ($ficheTechniqueVoiture->getTypeVehicule() === $this) {
                $ficheTechniqueVoiture->setTypeVehicule(null);
            }
        }

        return $this;
    }
}
