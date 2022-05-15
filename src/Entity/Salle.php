<?php

namespace App\Entity;

use App\Repository\SalleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=SalleRepository::class)
 * @UniqueEntity("Id")
 * message="cet id est deja utilisé")
 * @ORM\Table(name="salle", indexes={@ORM\Index(columns={"nom_s", "description"}, flags={"fulltext"})})
 */
class Salle
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @Assert\NotBlank (message="id est obligatoire")
     * @Assert\Range(min=1, max=255, maxMessage = "l'id max est 255 ",  minMessage = "l'id min est 1 ")
     * @Assert\Regex(
     *     pattern = "/^[0-9]{1,}\,{0,1}[0-9]{0,}$/",
     *     message = "L'id doit etre un entier positif."
     *     )
     */

    private $Id;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank (message="La surface est obligatoire")
     * @Assert\Range(min=50, max=100, maxMessage = "la surace maximum est 100 ",  minMessage = "la surface min est 50 ")
     * @Assert\Regex(
     *     pattern = "/^[0-9]{1,}\,{0,1}[0-9]{0,}$/",
     *     message = "La surface doit etre un entier positif."
     *     )
     */
    private $Surface;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=4, max=25, maxMessage = "le nom de la salle doit contenir un maximum 25 lettres",  minMessage = "le nom de la salle doit contenir un minimum de 4 lettres ")
     * @Assert\NotBlank (message="Le nom de la salle est obligatoire")
     */
    private $NomS;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank (message="la capacite est obligatoire")
     * @Assert\Range(min=1, max=20, maxMessage = "la capacité maximumal est 20 ",  minMessage = "La capacité min est 1 ")
     * @Assert\Regex(
     *     pattern = "/^[0-9]{1,}\,{0,1}[0-9]{0,}$/",
     *     message = "La capacité doit etre un entier positif."
     * )
     */
    private $CapaciteS;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Type(type="numeric",message =  "La valeur {{ value }} n'est pas valide, le type est {{ Type }} ")
     * @Assert\NotBlank (message="mentionné le nombre de cours maximal est obligatoire")
     * @Assert\Range(min=1, max=5, maxMessage = "le nombre de cours maximum est 5 ",  minMessage = "le min du nombre de cours maximal est de 1 ")
     * @Assert\Regex(
     *     pattern = "/^[0-9]{1,}\,{0,1}[0-9]{0,}$/",
     *     message = "le nombre max de cours doit etre un entier positif."
     *     )
     */
    private $nbCoursMaxS;

    /**
     * @ORM\OneToMany(targetEntity=Cours::class, mappedBy="salle",cascade={"all"},orphanRemoval=true)
     */
    private $cours;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=4, max=255, maxMessage = "La description doit contenir un maximum 255 lettres",  minMessage = "La description doit contenir un minimum de 4 lettres ")
     * @Assert\NotBlank (message="La description est obligatoire")
     */
    private $description;

    public function __construct()
    {
        $this->cours = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->Id;
    }
    public function setId(int $Id): self
    {
        $this->Id=$Id;
        return $this;
    }

    public function getSurface(): ?float
    {
        return $this->Surface;
    }

    public function setSurface(float $Surface): self
    {
        $this->Surface = $Surface;

        return $this;
    }

    public function getNomS(): ?string
    {
        return $this->NomS;
    }

    public function setNomS(string $NomS): self
    {
        $this->NomS = $NomS;

        return $this;
    }

    public function getCapaciteS(): ?int
    {
        return $this->CapaciteS;
    }

    public function setCapaciteS(int $CapaciteS): self
    {
        $this->CapaciteS = $CapaciteS;

        return $this;
    }

    public function getNbCoursMaxS(): ?int
    {
        return $this->nbCoursMaxS;
    }

    public function setNbCoursMaxS(int $nbCoursMaxS): self
    {
        $this->nbCoursMaxS = $nbCoursMaxS;

        return $this;
    }

    /**
     * @return Collection|Cours[]
     */
    public function getCours(): Collection
    {
        return $this->cours;
    }

    public function addCour(Cours $cour): self
    {
        if (!$this->cours->contains($cour)) {
            $this->cours[] = $cour;
            $cour->setSalle($this);
        }

        return $this;
    }

    public function removeCour(Cours $cour): self
    {
        if ($this->cours->removeElement($cour)) {
            // set the owning side to null (unless already changed)
            if ($cour->getSalle() === $this) {
                $cour->setSalle(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
