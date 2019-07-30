<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\PartenaireRepository")
 */
class Partenaire
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
    private $ninea;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $raisonsociale;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut;

    /**
<<<<<<< HEAD
     * @ORM\Column(type="string", length=255)
     */
    private $statut;

    
=======
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="partenaires")
     */
    private $adminsuper;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }
>>>>>>> caf3007fa196afcd1097c133d51d6d25bebd1d3b

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNinea(): ?int
    {
        return $this->ninea;
    }

    public function setNinea(int $ninea): self
    {
        $this->ninea = $ninea;

        return $this;
    }

    public function getRaisonsociale(): ?string
    {
        return $this->raisonsociale;
    }

    public function setRaisonsociale(string $raisonsociale): self
    {
        $this->raisonsociale = $raisonsociale;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getStatut(): ?string
<<<<<<< HEAD
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }   

}
=======
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getAdminsuper(): ?User
    {
        return $this->adminsuper;
    }

    public function setAdminsuper(?User $adminsuper): self
    {
        $this->adminsuper = $adminsuper;

        return $this;
    }
}
>>>>>>> caf3007fa196afcd1097c133d51d6d25bebd1d3b
