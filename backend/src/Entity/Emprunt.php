<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\EmpruntRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EmpruntRepository::class)]
class Emprunt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['emprunt:read', 'adherent:read', 'livre:read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Groups(['emprunt:read', 'emprunt:write', 'adherent:read', 'livre:read'])]
    private ?\DateTimeImmutable $dateEmprunt = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    #[Groups(['emprunt:read', 'emprunt:write', 'adherent:read', 'livre:read'])]
    private ?\DateTimeImmutable $dateRetour = null;

    #[ORM\ManyToOne(inversedBy: 'emprunts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['emprunt:read', 'emprunt:write'])]
    private Adherent $adherent;

    #[ORM\ManyToOne(inversedBy: 'emprunts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['emprunt:read', 'emprunt:write', 'adherent:read'])]
    private Livre $livre;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateEmprunt(): ?\DateTimeImmutable
    {
        return $this->dateEmprunt;
    }

    public function setDateEmprunt(\DateTimeImmutable $dateEmprunt): static
    {
        $this->dateEmprunt = $dateEmprunt;

        return $this;
    }

    public function getDateRetour(): ?\DateTimeImmutable
    {
        return $this->dateRetour;
    }

    public function setDateRetour(?\DateTimeImmutable $dateRetour): static
    {
        $this->dateRetour = $dateRetour;

        return $this;
    }

    public function getAdherent(): ?Adherent 
    {
        return $this->adherent;
    }

    public function setAdherent(?Adherent $adherent): static
    {
        $this->adherent = $adherent;

        return $this;
    }

    public function getLivre(): ?Livre
    {
        return $this->livre;
    }

    public function setLivre(?Livre $livre): static
    {
        $this->livre = $livre;

        return $this;
    }

    public function __toString(): string
    {   
        //check if date de retour is set
        if ($this->dateRetour === null) {
            return $this->id . ' ' . $this->dateEmprunt->format('Y-m-d') . ' ' . 'null' . ' ' . $this->adherent . ' ' . $this->livre;
        }
        return $this->id . ' ' . $this->dateEmprunt->format('Y-m-d') . ' ' . $this->dateRetour->format('Y-m-d') . ' ' . $this->adherent . ' ' . $this->livre;
    }
}
