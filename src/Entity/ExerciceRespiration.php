<?php

namespace App\Entity;

use AllowDynamicProperties;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\ExerciceRespirationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[AllowDynamicProperties] #[ApiResource]
#[ORM\Entity(repositoryClass: ExerciceRespirationRepository::class)]
class ExerciceRespiration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomExerciceRespiration = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $descriptionExerciceRespiration = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $dureeInspiration = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $dureeApnee = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $dureeExpiration = null;

    /**
     * @var Collection<int, Utilisateur>
     */
    #[ORM\ManyToMany(targetEntity: Utilisateur::class, inversedBy: 'idExerciceRespiration')]
    private Collection $idUtilisateur;

    public function __construct()
    {
        $this->idUtilisateur = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        $nomComplet = sprintf( "%s (%d ; %d ; %d)", $this->nomExerciceRespiration, $this->dureeInspiration, $this->dureeApnee, $this->dureeExpiration );
        return $nomComplet;
    }

    public function getNomComplet(): string
    {
        return $this;
    }

    public function getNomExerciceRespiration(): ?string
    {
        return $this->nomExerciceRespiration;
    }

    public function setNomExerciceRespiration(string $nomExerciceRespiration): static
    {
        $this->nomExerciceRespiration = $nomExerciceRespiration;
        return $this;
    }

    public function getDureeExerciceRespiration(): ?int
    {
        return $this->dureeExerciceRespiration;
    }

    public function setDureeExerciceRespiration(?int $dureeExerciceRespiration): static
    {
        $this->dureeExerciceRespiration = $dureeExerciceRespiration;
        return $this;
    }

    public function getDescriptionExerciceRespiration(): ?string
    {
        return $this->descriptionExerciceRespiration;
    }

    public function setDescriptionExerciceRespiration(?string $descriptionExerciceRespiration): static
    {
        $this->descriptionExerciceRespiration = $descriptionExerciceRespiration;
        return $this;
    }

    public function getParametresExerciceRespiration(): ?string
    {
        return $this->parametresExerciceRespiration;
    }

    public function setParametresExerciceRespiration(?string $parametresExerciceRespiration): static
    {
        $this->parametresExerciceRespiration = $parametresExerciceRespiration;
        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getIdUtilisateur(): Collection
    {
        return $this->idUtilisateur;
    }

    public function addIdUtilisateur(Utilisateur $idUtilisateur): static
    {
        if (!$this->idUtilisateur->contains($idUtilisateur)) {
            $this->idUtilisateur->add($idUtilisateur);
        }

        return $this;
    }

    public function removeIdUtilisateur(Utilisateur $idUtilisateur): static
    {
        $this->idUtilisateur->removeElement($idUtilisateur);

        return $this;
    }

    public function getDureeInspiration(): ?int
    {
        return $this->dureeInspiration;
    }

    public function setDureeInspiration(?int $dureeInspiration): void
    {
        $this->dureeInspiration = $dureeInspiration;
    }



    public function getDureeApnee(): ?int
    {
        return $this->dureeApnee;
    }

    public function setDureeApnee(?int $dureeApnee): void
    {
        $this->dureeApnee = $dureeApnee;
    }


    public function getDureeExpiration(): ?int
    {
        return $this->dureeExpiration;
    }

    public function setDureeExpiration(?int $dureeExpiration): void
    {
        $this->dureeExpiration = $dureeExpiration;
    }

}
