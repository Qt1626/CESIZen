<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\AdminLogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;



#[ApiResource]
#[ORM\Entity(repositoryClass: AdminLogRepository::class)]
class AdminLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $typeAction = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $dateAdminLog = null;


    #[ORM\ManyToOne(inversedBy: 'idAdminLog')]
    private ?Utilisateur $idUtilisateur = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeAction(): ?string
    {
        return $this->typeAction;
    }

    public function setTypeAction(string $typeAction): static
    {
        $this->typeAction = $typeAction;
        return $this;
    }

    public function getDateAdminLog(): ?\DateTimeImmutable
    {
        return $this->dateAdminLog;
    }

    public function setDateAdminLog(\DateTimeImmutable $dateAdminLog): static
    {
        $this->dateAdminLog = $dateAdminLog;
        return $this;
    }

    public function getIdUtilisateur(): ?Utilisateur
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(?Utilisateur $idUtilisateur): static
    {
        $this->idUtilisateur = $idUtilisateur;

        return $this;
    }
}
