<?php

namespace App\Entity;

use App\Repository\FicheVenteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FicheVenteRepository::class)]
class FicheVente
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateVente = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $prixVente = null;

    #[ORM\ManyToOne(inversedBy: 'ficheVentes')]
    #[ORM\JoinColumn(name: "type_vente_id", referencedColumnName: "idTypesVentes")]
    private ?TypeVentes $typeVente = null;

    #[ORM\ManyToOne(inversedBy: 'ficheVentes')]
    #[ORM\JoinColumn(name: "voiture_immatriculation", referencedColumnName: "immatriculation")]
    private ?Voiture $voiture = null;

    #[ORM\ManyToOne(inversedBy: 'ficheVentes')]
    #[ORM\JoinColumn(name: "type_vehicule_id", referencedColumnName: "idTypeVehicule")]
    private ?TypeVehicule $typeVehicule = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $numeroDossierVente = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $intermediaireVente = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $VendeurVN = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $VendeurVO = null;

    #[ORM\OneToMany(mappedBy: 'ficheVente', targetEntity: FicheTechniqueVoiture::class)]
    private Collection $ficheTechniqueVoitures;

    public function __construct()
    {
        $this->ficheTechniqueVoitures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateVente(): ?\DateTimeInterface
    {
        return $this->dateVente;
    }

    public function setDateVente(?\DateTimeInterface $dateVente): static
    {
        $this->dateVente = $dateVente;

        return $this;
    }

    public function getPrixVente(): ?string
    {
        return $this->prixVente;
    }

    public function setPrixVente(?string $prixVente): static
    {
        $this->prixVente = $prixVente;

        return $this;
    }

    public function getTypeVente(): ?TypeVentes
    {
        return $this->typeVente;
    }

    public function setTypeVente(?TypeVentes $typeVente): static
    {
        $this->typeVente = $typeVente;

        return $this;
    }

    public function getVoiture(): ?Voiture
    {
        return $this->voiture;
    }

    public function setVoiture(?Voiture $voiture): static
    {
        $this->voiture = $voiture;

        return $this;
    }

    public function getTypeVehicule(): ?TypeVehicule
    {
        return $this->typeVehicule;
    }

    public function setTypeVehicule(?TypeVehicule $typeVehicule): static
    {
        $this->typeVehicule = $typeVehicule;

        return $this;
    }

    public function getNumeroDossierVente(): ?string
    {
        return $this->numeroDossierVente;
    }

    public function setNumeroDossierVente(?string $numeroDossierVente): static
    {
        $this->numeroDossierVente = $numeroDossierVente;

        return $this;
    }

    public function getIntermediaireVente(): ?string
    {
        return $this->intermediaireVente;
    }

    public function setIntermediaireVente(?string $intermediaireVente): static
    {
        $this->intermediaireVente = $intermediaireVente;

        return $this;
    }

    public function getVendeurVN(): ?string
    {
        return $this->VendeurVN;
    }

    public function setVendeurVN(?string $VendeurVN): static
    {
        $this->VendeurVN = $VendeurVN;

        return $this;
    }

    public function getVendeurVO(): ?string
    {
        return $this->VendeurVO;
    }

    public function setVendeurVO(?string $VendeurVO): static
    {
        $this->VendeurVO = $VendeurVO;

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
            $ficheTechniqueVoiture->setFicheVente($this);
        }

        return $this;
    }

    public function removeFicheTechniqueVoiture(FicheTechniqueVoiture $ficheTechniqueVoiture): static
    {
        if ($this->ficheTechniqueVoitures->removeElement($ficheTechniqueVoiture)) {
            // set the owning side to null (unless already changed)
            if ($ficheTechniqueVoiture->getFicheVente() === $this) {
                $ficheTechniqueVoiture->setFicheVente(null);
            }
        }

        return $this;
    }
}
