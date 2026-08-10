<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
#[ApiResource]
#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateCreation = null;

    #[ORM\Column(nullable: true)]
    private ?bool $consentementDonne = null;

    #[ORM\Column(nullable: true)]
    private ?bool $estAdmin = null;

    #[ORM\OneToOne(inversedBy: 'utilisateur', cascade: ['persist', 'remove'])]
    private InfoUtilisateur|null $infoUtilisateur = null;

    /**
     * @var Collection<int, ExerciceRespiration>
     */
    #[ORM\ManyToMany(targetEntity: ExerciceRespiration::class, mappedBy: 'idUtilisateur')]
    private Collection $idExerciceRespiration;

    /**
     * @var Collection<int, AdminLog>
     */
    #[ORM\OneToMany(targetEntity: AdminLog::class,mappedBy: 'idUtilisateur')]
    private Collection $idAdminLog;

    public function __construct()
    {
        $this->idExerciceRespiration = new ArrayCollection();
        $this->idAdminLog = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCreation(): ?\DateTimeImmutable
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeImmutable $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function isConsentementDonne(): ?bool
    {
        return $this->consentementDonne;
    }

    public function setConsentementDonne(?bool $consentementDonne): static
    {
        $this->consentementDonne = $consentementDonne;

        return $this;
    }

    public function isEstAdmin(): ?bool
    {
        return $this->estAdmin;
    }

    public function setEstAdmin(?bool $estAdmin): static
    {
        $this->estAdmin = $estAdmin;

        return $this;
    }

    public function getInfoUtilisateur(): ?InfoUtilisateur
    {
        return $this->infoUtilisateur;
    }

    public function setInfoUtilisateur(?InfoUtilisateur $infoUtilisateur): static
    {
        $this->infoUtilisateur = $infoUtilisateur;

        return $this;
    }

    /**
     * @return Collection<int, ExerciceRespiration>
     */
    public function getIdExerciceRespiration(): Collection
    {
        return $this->idExerciceRespiration;
    }

    public function addIdExerciceRespiration(ExerciceRespiration $idExerciceRespiration): static
    {
        if (!$this->idExerciceRespiration->contains($idExerciceRespiration)) {
            $this->idExerciceRespiration->add($idExerciceRespiration);
            $idExerciceRespiration->addIdUtilisateur($this);
        }

        return $this;
    }

    public function removeIdExerciceRespiration(ExerciceRespiration $idExerciceRespiration): static
    {
        if ($this->idExerciceRespiration->removeElement($idExerciceRespiration)) {
            $idExerciceRespiration->removeIdUtilisateur($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, AdminLog>
     */
    public function getIdAdminLog(): Collection
    {
        return $this->idAdminLog;
    }

    public function addIdAdminLog(AdminLog $idAdminLog): static
    {
        if (!$this->idAdminLog->contains($idAdminLog)) {
            $this->idAdminLog->add($idAdminLog);
            $idAdminLog->setIdUtilisateur($this);
        }

        return $this;
    }

    public function removeIdAdminLog(AdminLog $idAdminLog): static
    {
        if ($this->idAdminLog->removeElement($idAdminLog)) {
            // set the owning side to null (unless already changed)
            if ($idAdminLog->getIdUtilisateur() === $this) {
                $idAdminLog->setIdUtilisateur(null);
            }
        }

        return $this;
    }
    public function __toString():string{
        if ($this->infoUtilisateur == null) {
            return '';
        }
        return $this->infoUtilisateur->getEmail();
    }


}
