<?php

namespace App\Entity;

use App\Repository\FicheTechniqueVoitureRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FicheTechniqueVoitureRepository::class)]
class FicheTechniqueVoiture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ficheTechniqueVoitures')]
    #[ORM\JoinColumn(name: "voiture_immatriculation", referencedColumnName: "immatriculation", nullable: true)]
    private ?Voiture $voiture = null;

    #[ORM\ManyToOne(inversedBy: 'ficheTechniqueVoitures')]
    #[ORM\JoinColumn(name: "energie_id", referencedColumnName: "idenergie", nullable: true)]
    private ?Energie $energie = null;

    #[ORM\ManyToOne(inversedBy: 'ficheTechniqueVoitures')]
    #[ORM\JoinColumn(name: "type_vehicule_id", referencedColumnName: "idTypeVehicule", nullable: true)]
    private ?TypeVehicule $typeVehicule = null;

    #[ORM\ManyToOne(inversedBy: 'ficheTechniqueVoitures')]
    #[ORM\JoinColumn(name: "type_vente_id", referencedColumnName: "idTypesVentes", nullable: true)]
    private ?TypeVentes $typeVente = null;

    #[ORM\ManyToOne(inversedBy: 'ficheTechniqueVoitures')]
    #[ORM\JoinColumn(nullable: true)]
    private ?FicheVente $ficheVente = null;

    #[ORM\ManyToOne(inversedBy: 'ficheTechniqueVoitures')]
    #[ORM\JoinColumn(name: "compte_affaire_id", referencedColumnName: "idcompte_affaire", nullable: true)]
    private ?CompteAffaire $compteAffaire = null;

    #[ORM\ManyToOne(inversedBy: 'ficheTechniqueVoitures')]
    #[ORM\JoinColumn(name: "compte_evenement_id", referencedColumnName: "idcompte_evenement", nullable: true)]
    private ?CompteEvenement $compteEvenement = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateEvenement = null;

    #[ORM\ManyToOne(inversedBy: 'ficheTechniqueVoitures')]
    #[ORM\JoinColumn(name: "origine_evenement_id", referencedColumnName: "idOrigineEvenement", nullable: true)]
    private ?OrigineEvenement $origineEvenement = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commentaireFacturation = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEnergie(): ?Energie
    {
        return $this->energie;
    }

    public function setEnergie(?Energie $energie): static
    {
        $this->energie = $energie;

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

    public function getTypeVente(): ?TypeVentes
    {
        return $this->typeVente;
    }

    public function setTypeVente(?TypeVentes $typeVente): static
    {
        $this->typeVente = $typeVente;

        return $this;
    }

    public function getFicheVente(): ?FicheVente
    {
        return $this->ficheVente;
    }

    public function setFicheVente(?FicheVente $ficheVente): static
    {
        $this->ficheVente = $ficheVente;

        return $this;
    }

    public function getCompteAffaire(): ?CompteAffaire
    {
        return $this->compteAffaire;
    }

    public function setCompteAffaire(?CompteAffaire $compteAffaire): static
    {
        $this->compteAffaire = $compteAffaire;

        return $this;
    }

    public function getCompteEvenement(): ?CompteEvenement
    {
        return $this->compteEvenement;
    }

    public function setCompteEvenement(?CompteEvenement $compteEvenement): static
    {
        $this->compteEvenement = $compteEvenement;

        return $this;
    }

    public function getDateEvenement(): ?\DateTimeInterface
    {
        return $this->dateEvenement;
    }

    public function setDateEvenement(\DateTimeInterface $dateEvenement): static
    {
        $this->dateEvenement = $dateEvenement;

        return $this;
    }

    public function getOrigineEvenement(): ?OrigineEvenement
    {
        return $this->origineEvenement;
    }

    public function setOrigineEvenement(?OrigineEvenement $origineEvenement): static
    {
        $this->origineEvenement = $origineEvenement;

        return $this;
    }

    public function getCommentaireFacturation(): ?string
    {
        return $this->commentaireFacturation;
    }

    public function setCommentaireFacturation(?string $commentaireFacturation): static
    {
        $this->commentaireFacturation = $commentaireFacturation;

        return $this;
    }
}
