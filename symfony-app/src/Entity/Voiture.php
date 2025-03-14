<?php

namespace App\Entity;

use App\Repository\VoitureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VoitureRepository::class)]
class Voiture
{
    #[ORM\Id]
    #[ORM\Column(length: 50)]
    private ?string $immatriculation = null;

    #[ORM\Column(length: 255)]
    private ?string $marque = null;

    #[ORM\Column(length: 255)]
    private ?string $modele = null;

    #[ORM\Column(length: 255)]
    private ?string $versions = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateMiseEnCirculation = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateAchatEtLivraison = null;

    #[ORM\Column]
    private ?int $kilometrage = null;

    #[ORM\ManyToOne(inversedBy: 'voitures')]
    #[ORM\JoinColumn(name: "energie_id", referencedColumnName: "idenergie")]
    private ?Energie $energie = null;

    #[ORM\ManyToOne(inversedBy: 'voitures')]
    #[ORM\JoinColumn(name: "proprio_id", referencedColumnName: "id")]
    private ?Proprio $proprio = null;

    #[ORM\OneToMany(mappedBy: 'voiture', targetEntity: FicheVente::class)]
    private Collection $ficheVentes;

    #[ORM\OneToMany(mappedBy: 'voiture', targetEntity: FicheTechniqueVoiture::class)]
    private Collection $ficheTechniqueVoitures;

    public function __construct()
    {
        $this->ficheVentes = new ArrayCollection();
        $this->ficheTechniqueVoitures = new ArrayCollection();
    }

    public function getImmatriculation(): ?string
    {
        return $this->immatriculation;
    }

    public function setImmatriculation(string $immatriculation): static
    {
        $this->immatriculation = $immatriculation;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): static
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(string $modele): static
    {
        $this->modele = $modele;

        return $this;
    }

    public function getVersions(): ?string
    {
        return $this->versions;
    }

    public function setVersions(string $versions): static
    {
        $this->versions = $versions;

        return $this;
    }

    public function getDateMiseEnCirculation(): ?\DateTimeInterface
    {
        return $this->dateMiseEnCirculation;
    }

    public function setDateMiseEnCirculation(\DateTimeInterface $dateMiseEnCirculation): static
    {
        $this->dateMiseEnCirculation = $dateMiseEnCirculation;

        return $this;
    }

    public function getDateAchatEtLivraison(): ?\DateTimeInterface
    {
        return $this->dateAchatEtLivraison;
    }

    public function setDateAchatEtLivraison(\DateTimeInterface $dateAchatEtLivraison): static
    {
        $this->dateAchatEtLivraison = $dateAchatEtLivraison;

        return $this;
    }

    public function getKilometrage(): ?int
    {
        return $this->kilometrage;
    }

    public function setKilometrage(int $kilometrage): static
    {
        $this->kilometrage = $kilometrage;

        return $this;
    }

    public function getEnergie(): ?Energie
    {
        return $this->energie;
    }

    public function setEnergie(?Energie $energie): static
    {
        $this->energie = $energie;

        return $this;
    }

    public function getProprio(): ?Proprio
    {
        return $this->proprio;
    }

    public function setProprio(?Proprio $proprio): static
    {
        $this->proprio = $proprio;

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
            $ficheVente->setVoiture($this);
        }

        return $this;
    }

    public function removeFicheVente(FicheVente $ficheVente): static
    {
        if ($this->ficheVentes->removeElement($ficheVente)) {
            // set the owning side to null (unless already changed)
            if ($ficheVente->getVoiture() === $this) {
                $ficheVente->setVoiture(null);
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
            $ficheTechniqueVoiture->setVoiture($this);
        }

        return $this;
    }

    public function removeFicheTechniqueVoiture(FicheTechniqueVoiture $ficheTechniqueVoiture): static
    {
        if ($this->ficheTechniqueVoitures->removeElement($ficheTechniqueVoiture)) {
            // set the owning side to null (unless already changed)
            if ($ficheTechniqueVoiture->getVoiture() === $this) {
                $ficheTechniqueVoiture->setVoiture(null);
            }
        }

        return $this;
    }
}
