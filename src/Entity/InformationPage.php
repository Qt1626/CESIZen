<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\InformationPageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: InformationPageRepository::class)]
class InformationPage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titrePage = null;

    #[ORM\Column]
    private ?int $ordreAffichage = null;

    #[ORM\Column]
    private ?bool $estVisible = null;

    #[ORM\Column(type: 'text')]
    private ?string $contenuPage = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $commentaire = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitrePage(): ?string
    {
        return $this->titrePage;
    }

    public function setTitrePage(string $titrePage): static
    {
        $this->titrePage = $titrePage;
        return $this;
    }

    public function getOrdreAffichage(): ?int
    {
        return $this->ordreAffichage;
    }

    public function setOrdreAffichage($ordreAffichage): static
    {
        $this->ordreAffichage = (int)$ordreAffichage;
        return $this;
    }


    public function isEstVisible(): ?bool
    {
        return $this->estVisible;
    }

    public function setEstVisible(bool $estVisible): static
    {
        $this->estVisible = $estVisible;
        return $this;
    }

    public function getContenuPage(): ?string
    {
        return $this->contenuPage;
    }

    public function setContenuPage(string $contenuPage): static
    {
        $this->contenuPage = $contenuPage;
        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }
}
