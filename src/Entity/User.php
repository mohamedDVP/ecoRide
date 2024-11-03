<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $date_naissance = null;

    #[ORM\Column(type: Types::BLOB)]
    private $photo = null;

    #[ORM\Column(length: 255)]
    private ?string $pseudo = null;

    /**
     * @var Collection<int, Voiture>
     */
    #[ORM\OneToMany(targetEntity: Voiture::class, mappedBy: 'id_user')]
    private Collection $voitures;

    /**
     * @var Collection<int, Configuration>
     */
    #[ORM\OneToMany(targetEntity: Configuration::class, mappedBy: 'idUser')]
    private Collection $configurations;

    /**
     * @var Collection<int, Role>
     */
    #[ORM\ManyToMany(targetEntity: Role::class, inversedBy: 'user')]
    private Collection $idRole;

    /**
     * @var Collection<int, Avis>
     */
    #[ORM\ManyToMany(targetEntity: Avis::class, inversedBy: 'user')]
    private Collection $idAvis;

    /**
     * @var Collection<int, Covoiturage>
     */
    #[ORM\ManyToMany(targetEntity: Covoiturage::class, inversedBy: 'users')]
    private Collection $idCovoiturage;

    public function __construct()
    {
        $this->idRole = new ArrayCollection();
        $this->idAvis = new ArrayCollection();
        $this->idCovoiturage = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeImmutable
    {
        return $this->date_naissance;
    }

    public function setDateNaissance(\DateTimeImmutable $date_naissance): static
    {
        $this->date_naissance = $date_naissance;

        return $this;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

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
            $voiture->setIdUser($this);
        }

        return $this;
    }

    public function removeVoiture(Voiture $voiture): static
    {
        if ($this->voitures->removeElement($voiture)) {
            // set the owning side to null (unless already changed)
            if ($voiture->getIdUser() === $this) {
                $voiture->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Configuration>
     */
    public function getConfigurations(): Collection
    {
        return $this->configurations;
    }

    public function addConfiguration(Configuration $configuration): static
    {
        if (!$this->configurations->contains($configuration)) {
            $this->configurations->add($configuration);
            $configuration->setIdUser($this);
        }

        return $this;
    }

    public function removeConfiguration(Configuration $configuration): static
    {
        if ($this->configurations->removeElement($configuration)) {
            // set the owning side to null (unless already changed)
            if ($configuration->getIdUser() === $this) {
                $configuration->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Role>
     */
    public function getIdRole(): Collection
    {
        return $this->idRole;
    }

    public function addIdRole(Role $idRole): static
    {
        if (!$this->idRole->contains($idRole)) {
            $this->idRole->add($idRole);
        }

        return $this;
    }

    public function removeIdRole(Role $idRole): static
    {
        $this->idRole->removeElement($idRole);

        return $this;
    }

    /**
     * @return Collection<int, Avis>
     */
    public function getIdAvis(): Collection
    {
        return $this->idAvis;
    }

    public function addIdAvis(Avis $idAvis): static
    {
        if (!$this->idAvis->contains($idAvis)) {
            $this->idAvis->add($idAvis);
        }

        return $this;
    }

    public function removeIdAvis(Avis $idAvis): static
    {
        $this->idAvis->removeElement($idAvis);

        return $this;
    }

    /**
     * @return Collection<int, Covoiturage>
     */
    public function getIdCovoiturage(): Collection
    {
        return $this->idCovoiturage;
    }

    public function addIdCovoiturage(Covoiturage $idCovoiturage): static
    {
        if (!$this->idCovoiturage->contains($idCovoiturage)) {
            $this->idCovoiturage->add($idCovoiturage);
        }

        return $this;
    }

    public function removeIdCovoiturage(Covoiturage $idCovoiturage): static
    {
        $this->idCovoiturage->removeElement($idCovoiturage);

        return $this;
    }

}
