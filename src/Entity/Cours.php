<?php

namespace App\Entity;

use App\Repository\CoursRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\MinLength;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=CoursRepository::class)
 * @UniqueEntity("Id" ,
 *     message="cet id est deja utilisé")
 */
class Cours
{
    const HEAT =[
        'Débutant'=>'Débutant',
        'Intermédiaire'=>'Intermédiaire',
        'Avancé'=>'Avancé'
    ];
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @Assert\NotBlank (message="id est obligatoire")
     * @Assert\Range(min=0, max=255, maxMessage = "l'id doit contenir au maximum 255 chiffres",  minMessage = "l'id doit contenir au min 0 chiffres")
     * @Assert\Regex(
     *     pattern = "/^[0-9]{1,}\,{0,1}[0-9]{0,}$/",
     *     message = "L'id doit etre un entier positif."
     *     )
     */
    private $Id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=4, max=25 , maxMessage = "le type doit contenir au maximum 25 caracteres",  minMessage = "le type doit contenir au min 4 caracteres")
     * @Assert\NotBlank (message="Le type est obligatoire")
     * @Assert\Regex(
     *     pattern = "/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùÞúûüųūæÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$/u
    ",
     *     message = "Le type doit etre composé de caractéres."
     *     )
     */
    private $TypeC;

    /**
     * @ORM\Column(type="date")
     */
    private $DateC;

    /**
     * @ORM\Column(type="time" )
     */
    private $HeureDebutC;

    /**
     * @ORM\Column(type="time")
     */
    private $DureeC;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $NiveauC;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank (message="image est obligatoire")
     */
    private $ImageC;

    /**
     * @ORM\ManyToOne(targetEntity=Salle::class, inversedBy="cours")
     */
    private $salle;

    /**
     * @ORM\Column(type="string", length=7)
     */
    private $couleur;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank (message="Le nombre de serie est obligatoire")
     */
    private $SerieC;

    public function getId(): ?int
    {
        return $this->Id;
    }

    public function setId(int $Id): self
    {
        $this->Id=$Id;
        return $this;
    }

    public function getTypeC(): ?string
    {
        return $this->TypeC;
    }

    public function setTypeC(string $TypeC): self
    {
        $this->TypeC = $TypeC;

        return $this;
    }

    public function getDateC(): ?\DateTimeInterface
    {
        return $this->DateC;
    }

    public function setDateC(\DateTimeInterface $DateC): self
    {
        $this->DateC = $DateC;

        return $this;
    }

    public function getHeureDebutC(): ?\DateTimeInterface
    {
        return $this->HeureDebutC;
    }

    public function setHeureDebutC(\DateTimeInterface $HeureDebutC): self
    {
        $this->HeureDebutC = $HeureDebutC;

        return $this;
    }

    public function getDureeC(): ?\DateTimeInterface
    {
        return $this->DureeC;
    }

    public function setDureeC(\DateTimeInterface $DureeC): self
    {
        $this->DureeC = $DureeC;

        return $this;
    }

    public function getNiveauC(): ?string
    {
        return $this->NiveauC;
    }

    public function setNiveauC(string $NiveauC): self
    {
        $this->NiveauC = $NiveauC;

        return $this;
    }

    public function getImageC(): ?string
    {
        return $this->ImageC;
    }

    public function setImageC(string $ImageC):self
    {
        $this->ImageC = $ImageC;

        return $this;
    }

    public function getSalle(): ?Salle
    {
        return $this->salle;
    }

    public function setSalle(?Salle $salle): self
    {
        $this->salle = $salle;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getSerieC(): ?int
    {
        return $this->SerieC;
    }

    public function setSerieC(?int $SerieC): self
    {
        $this->SerieC = $SerieC;

        return $this;
    }
}
