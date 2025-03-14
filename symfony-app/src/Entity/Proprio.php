<?php

namespace App\Entity;

use App\Repository\ProprioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProprioRepository::class)]
class Proprio
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    private ?string $prenom = null;

    #[ORM\Column(length: 150)]
    private ?string $email = null;

    #[ORM\Column(length: 150)]
    private ?string $NumEtNomVoie = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $complementAdresse = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $telephoneDomicile = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $telephonePortable = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $telephoneJob = null;

    #[ORM\Column(length: 10)]
    private ?string $code_postal = null;

    #[ORM\Column(length: 100)]
    private ?string $ville = null;

    #[ORM\ManyToOne(inversedBy: 'proprios')]
    #[ORM\JoinColumn(name: "idLibelleCivilite", referencedColumnName: "idLibelleCivilite")]
    private ?LibelleCivilite $libelleCivilite = null;

    #[ORM\ManyToOne(inversedBy: 'proprios')]
    #[ORM\JoinColumn(name: "idTypeProspect", referencedColumnName: "idTypeProspect")]
    private ?TypeProspect $typeProspect = null;

    #[ORM\OneToMany(mappedBy: 'proprio', targetEntity: Voiture::class)]
    private Collection $voitures;

    public function __construct()
    {
        $this->voitures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getNumEtNomVoie(): ?string
    {
        return $this->NumEtNomVoie;
    }

    public function setNumEtNomVoie(string $NumEtNomVoie): static
    {
        $this->NumEtNomVoie = $NumEtNomVoie;

        return $this;
    }

    public function getComplementAdresse(): ?string
    {
        return $this->complementAdresse;
    }

    public function setComplementAdresse(?string $complementAdresse): static
    {
        $this->complementAdresse = $complementAdresse;

        return $this;
    }

    public function getTelephoneDomicile(): ?string
    {
        return $this->telephoneDomicile;
    }

    public function setTelephoneDomicile(?string $telephoneDomicile): static
    {
        $this->telephoneDomicile = $telephoneDomicile;

        return $this;
    }

    public function getTelephonePortable(): ?string
    {
        return $this->telephonePortable;
    }

    public function setTelephonePortable(?string $telephonePortable): static
    {
        $this->telephonePortable = $telephonePortable;

        return $this;
    }

    public function getTelephoneJob(): ?string
    {
        return $this->telephoneJob;
    }

    public function setTelephoneJob(?string $telephoneJob): static
    {
        $this->telephoneJob = $telephoneJob;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->code_postal;
    }

    public function setCodePostal(string $code_postal): static
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getLibelleCivilite(): ?LibelleCivilite
    {
        return $this->libelleCivilite;
    }

    public function setLibelleCivilite(?LibelleCivilite $libelleCivilite): static
    {
        $this->libelleCivilite = $libelleCivilite;

        return $this;
    }

    public function getTypeProspect(): ?TypeProspect
    {
        return $this->typeProspect;
    }

    public function setTypeProspect(?TypeProspect $typeProspect): static
    {
        $this->typeProspect = $typeProspect;

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
            $voiture->setProprio($this);
        }

        return $this;
    }

    public function removeVoiture(Voiture $voiture): static
    {
        if ($this->voitures->removeElement($voiture)) {
            // set the owning side to null (unless already changed)
            if ($voiture->getProprio() === $this) {
                $voiture->setProprio(null);
            }
        }

        return $this;
    }
}
