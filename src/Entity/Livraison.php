<?php

namespace App\Entity;

use App\Repository\LivraisonRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=LivraisonRepository::class)
 */
class Livraison
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * * @Assert\NotEqualTo(
     *     value = 0
     *     )
     */
    private $numL;

    /**
     * @ORM\Column(type="string", length=255)
     *   * @Assert\Length(
     *      min = 4,
     *      max = 20,
     *      minMessage = "Your name must be at least {{ limit }} characters long",
     *      maxMessage = "Your  name cannot be longer than {{ limit }} characters",
     *      allowEmptyString = false
     * )
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

    /**
     * @ORM\ManyToOne(targetEntity=Commandes::class, inversedBy="livraisons")
     */
    private $numcmd;
    public function getid(): ?string
    {
        return $this->id;
    }

    public function setid(string $id): self
    {
        $this->id = $id;

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
    public function getNumL(): ?int
    {
        return $this->numL;
    }

    public function setNumL(int $numL): self
    {
        $this->numL = $numL;

        return $this;
    }
    public function getNomcm(): ?commandes
    {
        return $this->nomcm;
    }

    public function setNomcm(?commandes $nomcm): self
    {
        $this->nomcm = $nomcm;

        return $this;
    }

    public function getNumcmd(): ?Commandes
    {
        return $this->numcmd;
    }

    public function setNumcmd(?Commandes $numcmd): self
    {
        $this->numcmd = $numcmd;

        return $this;
    }
}
