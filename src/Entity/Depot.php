<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\DepotRepository")
 */
class Depot
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $montant;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datedepot;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Comptebancaire", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $comptebancaire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="depot")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDatedepot(): ?\DateTimeInterface
    {
        return $this->datedepot;
    }

    public function setDatedepot(\DateTimeInterface $datedepot): self
    {
        $this->datedepot = $datedepot;

        return $this;
    }

    public function getComptebancaire(): ?Comptebancaire
    {
        return $this->comptebancaire;
    }

    public function setComptebancaire(Comptebancaire $comptebancaire): self
    {
        $this->comptebancaire = $comptebancaire;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
