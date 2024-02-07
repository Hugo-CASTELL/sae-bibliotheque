<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\LivreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Get;

#[ORM\Entity(repositoryClass: LivreRepository::class)]
#[ApiResource(operations: [
    new Get(name: 'app_api_livre'),
    new Get(name: 'app_api_livre_show')
])]
class Livre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['livre:read', 'categorie:read', 'auteur:read', 'emprunt:read', 'reservations:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['livre:read', 'livre:write', 'categorie:read', 'categorie:write', 'auteur:read', 'auteur:write', 'emprunt:read', 'emprunt:write', 'reservations:read'])]
    private ?string $titre = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Groups(['livre:read', 'livre:write', 'categorie:read', 'categorie:write', 'auteur:read', 'auteur:write', 'emprunt:read', 'emprunt:write', 'reservations:read'])]
    private ?\DateTimeImmutable $dateSortie = null;

    #[ORM\Column(length: 255)]
    #[Groups(['livre:read', 'livre:write', 'categorie:read', 'categorie:write', 'auteur:read', 'auteur:write', 'emprunt:read', 'emprunt:write', 'reservations:read'])]
    private ?string $langue = null;

    #[ORM\Column(length: 255)]
    #[Groups(['livre:read', 'livre:write', 'categorie:read', 'categorie:write', 'auteur:read', 'auteur:write', 'emprunt:read', 'emprunt:write', 'reservations:read'])]
    private ?string $photoCouverture = null;

    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'livres')]
    #[Groups(['livre:read', 'livre:write'])]
    private Collection $categories;

    #[ORM\ManyToMany(targetEntity: Auteur::class, inversedBy: 'livres')]
    #[Groups(['livre:read', 'livre:write'])]
    private Collection $auteurs;

    #[ORM\OneToOne(mappedBy: 'livre')]
    #[Groups(['livre:read', 'livre:write'])]
    private ?Reservations $reservations = null;

    #[ORM\OneToMany(mappedBy: 'livre', targetEntity: Emprunt::class)]
    #[Groups(['livre:read'])]
    private Collection $emprunts;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->auteurs = new ArrayCollection();
        $this->emprunts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDateSortie(): ?\DateTimeImmutable
    {
        return $this->dateSortie;
    }

    public function setDateSortie(\DateTimeImmutable $dateSortie): static
    {
        $this->dateSortie = $dateSortie;

        return $this;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): static
    {
        $this->langue = $langue;

        return $this;
    }

    public function getPhotoCouverture(): ?string
    {
        return $this->photoCouverture;
    }

    public function setPhotoCouverture(string $photoCouverture): static
    {
        $this->photoCouverture = $photoCouverture;

        return $this;
    }

    /**
     * @return Collection<int, Categorie>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categorie $category): static
    {
        if (!$this->categories->contains($category) && !($this->categories->count() >= 3)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Categorie $category): static
    {
        if(!($this->categories->count() <= 1)){
            $this->categories->removeElement($category);
        }

        return $this;
    }

    /**
     * @return Collection<int, Auteur>
     */
    public function getAuteurs(): Collection
    {
        return $this->auteurs;
    }

    public function addAuteur(Auteur $auteur): static
    {
        if (!$this->auteurs->contains($auteur)) {
            $this->auteurs->add($auteur);
        }

        return $this;
    }

    public function removeAuteur(Auteur $auteur): static
    {
        $this->auteurs->removeElement($auteur);

        return $this;
    }

    public function getReservations(): ?Reservations
    {
        return $this->reservations;
    }

    public function setReservations(Reservations $reservations): static
    {
        // set the owning side of the relation if necessary
        if ($reservations->getLivre() !== $this) {
            $reservations->setLivre($this);
        }

        $this->reservations = $reservations;

        return $this;
    }

    /**
     * @return Collection<int, Emprunt>
     */
    public function getEmprunts(): Collection
    {
        return $this->emprunts;
    }

    public function addEmprunt(Emprunt $emprunt): static
    {
        if (!$this->emprunts->contains($emprunt)) {
            $this->emprunts->add($emprunt);
            $emprunt->setLivre($this);
        }

        return $this;
    }

    public function removeEmprunt(Emprunt $emprunt): static
    {
        if ($this->emprunts->removeElement($emprunt)) {
            // set the owning side to null (unless already changed)
            if ($emprunt->getLivre() === $this) {
                $emprunt->setLivre(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->titre;
    }

    public function isDisponible(): bool
    {
        if($this->reservations !== null){
            return false;
        }

        foreach($this->emprunts as $emprunt){
            if($emprunt->getDateRetour() === null){
                return false;
            }
        }

        return true;
    }

    public function isReservedBy(Adherent $adherent): bool
    {
        return $this->reservations !== null & $this->reservations->getAdherent() === $adherent;
    }
}
