<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\AuteurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Get; 

#[ORM\Entity(repositoryClass: AuteurRepository::class)]
#[ApiResource(operations: [
    new Get(name: 'app_api_auteur'),
    new Get(name: 'app_api_auteur_show'),
])]
class Auteur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['auteur:read', 'livre:read', 'adherent:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['auteur:read', 'auteur:write', 'livre:read', 'livre:write', 'adherent:read'])]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Groups(['auteur:read', 'auteur:write', 'livre:read', 'livre:write', 'adherent:read'])]
    private ?string $prenom = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    #[Groups(['auteur:read', 'auteur:write', 'livre:read', 'livre:write', 'adherent:read'])]
    private ?\DateTimeImmutable $dateNaissance = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    #[Groups(['auteur:read', 'auteur:write', 'livre:read', 'livre:write', 'adherent:read'])]
    private ?\DateTimeImmutable $dateDeces = null;

    #[ORM\Column(length: 255)]
    #[Groups(['auteur:read', 'auteur:write', 'livre:read', 'livre:write', 'adherent:read'])]
    private ?string $nationalite = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['auteur:read', 'auteur:write', 'livre:read', 'livre:write', 'adherent:read'])]
    private ?string $photo = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['auteur:read', 'auteur:write', 'livre:read', 'livre:write', 'adherent:read'])]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: Livre::class, mappedBy: 'auteurs')]
    #[Groups(['auteur:read'])]
    private Collection $livres;

    public function __construct()
    {
        $this->livres = new ArrayCollection();
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

    public function getDateNaissance(): ?\DateTimeImmutable
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(?\DateTimeImmutable $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getDateDeces(): ?\DateTimeImmutable
    {
        return $this->dateDeces;
    }

    public function setDateDeces(?\DateTimeImmutable $dateDeces): static
    {
        $this->dateDeces = $dateDeces;

        return $this;
    }

    public function getNationalite(): ?string
    {
        return $this->nationalite;
    }

    public function setNationalite(string $nationalite): static
    {
        $this->nationalite = $nationalite;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Livre>
     */
    public function getLivres(): Collection
    {
        return $this->livres;
    }

    public function addLivre(Livre $livre): static
    {
        if (!$this->livres->contains($livre)) {
            $this->livres->add($livre);
            $livre->addAuteur($this);
        }

        return $this;
    }

    public function removeLivre(Livre $livre): static
    {
        if ($this->livres->removeElement($livre)) {
            $livre->removeAuteur($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->nom . ' ' . $this->prenom;
    }
}
