<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 */
class Produit
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * * @Assert\NotEqualTo(
     *     value = 0
     * )
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * * @Assert\Length(
     *      min = 4,
     *      max = 20,
     *      minMessage = "Your  nameP must be at least {{ limit }} characters long",
     *      maxMessage = "Your nameP cannot be longer than {{ limit }} characters",
     *      allowEmptyString = false
     * )
     */
    private $nomP;

    /**
     * @ORM\Column(type="integer")
     * * @Assert\NotEqualTo(
     *     value = 0
     * )
     */
    private $nombre;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @ORM\Column(type="integer")
     */
    private $reduction;

    /**
     * @ORM\Column(type="date")
     * * @Assert\Date
     * @var string A "Y-m-d" formatted value
     */
    private $dateP;

    /**
     * @ORM\Column(type="string", length=255)
     * * @Assert\Length(
     *      min = 4,
     *      max = 20,
     *      minMessage = "Your marque must be at least {{ limit }} characters long",
     *      maxMessage = "Your marque cannot be longer than {{ limit }} characters",
     *      allowEmptyString = false
     * )
     */
    private $marque;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="produits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity=Panniers::class, mappedBy="nmp")
     */
    private $panniers;

    public function __construct()
    {
        $this->panniers = new ArrayCollection();
    }




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?int
    {
        return $this->libelle;
    }

    public function setLibelle(int $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getNomP(): ?string
    {
        return $this->nomP;
    }

    public function setNomP(string $nomP): self
    {
        $this->nomP = $nomP;

        return $this;
    }

    public function getNombre(): ?int
    {
        return $this->nombre;
    }
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }
    public function setNombre(int $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getReduction(): ?int
    {
        return $this->reduction;
    }

    public function setReduction(int $reduction): self
    {
        $this->reduction = $reduction;

        return $this;
    }

    public function getDateP(): ?\DateTimeInterface
    {
        return $this->dateP;
    }

    public function setDateP(\DateTimeInterface $dateP): self
    {
        $this->dateP = $dateP;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Panniers>
     */
    public function getPanniers(): Collection
    {
        return $this->panniers;
    }

    public function addPannier(Panniers $pannier): self
    {
        if (!$this->panniers->contains($pannier)) {
            $this->panniers[] = $pannier;
            $pannier->setNmp($this);
        }

        return $this;
    }

    public function removePannier(Panniers $pannier): self
    {
        if ($this->panniers->removeElement($pannier)) {
            // set the owning side to null (unless already changed)
            if ($pannier->getNmp() === $this) {
                $pannier->setNmp(null);
            }
        }

        return $this;
    }




}
