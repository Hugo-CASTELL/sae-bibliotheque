<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Get;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
#[ApiResource(operations: [
    new Get(name: 'app_api_categorie'),
    new Get(name: 'app_api_categorie_show')
])]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['categorie:read', 'livre:read', 'livre:write', 'adherent:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['categorie:read', 'categorie:write', 'livre:read', 'livre:write', 'adherent:read'])]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['categorie:read', 'categorie:write', 'livre:read', 'livre:write', 'adherent:read'])]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: Livre::class, mappedBy: 'categories')]
    #[Groups(['categorie:read'])]
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
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
            $livre->addCategory($this);
        }

        return $this;
    }

    public function removeLivre(Livre $livre): static
    {
        if ($this->livres->removeElement($livre)) {
            $livre->removeCategory($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->nom;
    }
}
