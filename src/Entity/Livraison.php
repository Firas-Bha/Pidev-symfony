<?php

namespace App\Entity;

use JsonSerializable;
use App\Repository\LivraisonRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LivraisonRepository::class)
 */
class Livraison implements JsonSerializable
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $numL;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomLivreur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telLivreur;

    /**
     * @ORM\Column(type="date")
     */
    private $dateLivraison;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumL(): ?int
    {
        return $this->numL;
    }

    public function setNumL(int $numL): self
    {
        $this->numL = $numL;

        return $this;
    }

    public function getNomLivreur(): ?string
    {
        return $this->nomLivreur;
    }

    public function setNomLivreur(string $nomLivreur): self
    {
        $this->nomLivreur = $nomLivreur;

        return $this;
    }

    public function getTelLivreur(): ?string
    {
        return $this->telLivreur;
    }

    public function setTelLivreur(string $telLivreur): self
    {
        $this->telLivreur = $telLivreur;

        return $this;
    }

    public function getDateLivraison(): ?\DateTimeInterface
    {
        return $this->dateLivraison;
    }

    public function setDateLivraison(\DateTimeInterface $dateLivraison): self
    {
        $this->dateLivraison = $dateLivraison;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'numL' => $this->numL,
            'nomLivreur' => $this->nomLivreur,
            'telLivreur' => $this->telLivreur,
            'dateLivraison' => $this->dateLivraison->format("d-m-Y"),
        );
    }

    public function setUp($numL, $nomLivreur, $telLivreur, $dateLivraison)
    {
        $this->numL = $numL;
        $this->nomLivreur = $nomLivreur;
        $this->telLivreur = $telLivreur;
        $this->dateLivraison = $dateLivraison;
    }
}
