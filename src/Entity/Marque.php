<?php

namespace App\Entity;

use App\Repository\MarqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MarqueRepository::class)]
class Marque
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $libelle = null;

    /**
     * @var Collection<int, Voiture>
     */
    #[ORM\OneToMany(targetEntity: Voiture::class, mappedBy: 'marque')]
    private Collection $voitureID;

    public function __construct()
    {
        $this->voitureID = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection<int, Voiture>
     */
    public function getVoitureID(): Collection
    {
        return $this->voitureID;
    }

    public function addVoitureID(Voiture $voitureID): static
    {
        if (!$this->voitureID->contains($voitureID)) {
            $this->voitureID->add($voitureID);
            $voitureID->setMarque($this);
        }

        return $this;
    }

    public function removeVoitureID(Voiture $voitureID): static
    {
        if ($this->voitureID->removeElement($voitureID)) {
            // set the owning side to null (unless already changed)
            if ($voitureID->getMarque() === $this) {
                $voitureID->setMarque(null);
            }
        }

        return $this;
    }
}
