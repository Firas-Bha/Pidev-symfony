<?php

namespace App\Entity;

use App\Repository\PanniersRepository;
use Doctrine\ORM\Mapping as ORM;
use FontLib\TrueType\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection ;


/**
 * @ORM\Entity(repositoryClass=PanniersRepository::class)
 *
 */
class Panniers
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="champ obligatoir")
     */
    private $etat;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="champ obligatoir")
     */
    private $categorie;

    /**
     * @ORM\Column(type="date")
     */
    private $dateexp;



    /**
     * @ORM\OneToMany(targetEntity=Commandes::class, mappedBy="Panniers")
     */
    private $commandes;

    /**
     * @ORM\ManyToOne(targetEntity=Produit::class, inversedBy="panniers")
     */
    private $nmp;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getDateexp(): ?\DateTimeInterface
    {
        return $this->dateexp;
    }

    public function setDateexp(\DateTimeInterface $dateexp): self
    {
        $this->dateexp = $dateexp;

        return $this;
    }



    /**
     * @return \Doctrine\Common\Collections\Collection|Commandes[]
     */
    public function getCommandes(): \Doctrine\Common\Collections\Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commandes $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->setPanniers($this);
        }

        return $this;
    }

    public function removeCommande(Commandes $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getPanniers() === $this) {
                $commande->setPanniers(null);
            }
        }

        return $this;
    }

    public function getNmp(): ?Produit
    {
        return $this->nmp;
    }

    public function setNmp(?Produit $nmp): self
    {
        $this->nmp = $nmp;

        return $this;
    }

}
