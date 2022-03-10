<?php

namespace App\Entity;

use App\Repository\StaffRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=StaffRepository::class)
 */
class Staff
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters",
     *      allowEmptyString = false)
     */
    private $nom_staff;

    /**
     * @ORM\Column(type="string", length=255)
     *   @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters",
     *      allowEmptyString = false)
     *
     */
    private $prenom_staff;

    /**
     * @ORM\Column(type="date")
     */
    private $date_de_naiss_s;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sexe;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\Column(type="integer")
     *
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     *
     */
    private $Email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *
     *      allowEmptyString = false)
     */
    private $poste;

    /**
     * @ORM\Column(type="string", length=255)
     *
     *
     *
     */
    private $date_entree;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomStaff(): ?string
    {
        return $this->nom_staff;
    }

    public function setNomStaff(string $nom_staff): self
    {
        $this->nom_staff = $nom_staff;

        return $this;
    }

    public function getPrenomStaff(): ?string
    {
        return $this->prenom_staff;
    }

    public function setPrenomStaff(string $prenom_staff): self
    {
        $this->prenom_staff = $prenom_staff;

        return $this;
    }

    public function getDateDeNaissS(): ?\DateTimeInterface
    {
        return $this->date_de_naiss_s;
    }

    public function setDateDeNaissS(\DateTimeInterface $date_de_naiss_s): self
    {
        $this->date_de_naiss_s = $date_de_naiss_s;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

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

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(int $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): self
    {
        $this->Email = $Email;

        return $this;
    }

    public function getPoste(): ?string
    {
        return $this->poste;
    }

    public function setPoste(string $poste): self
    {
        $this->poste = $poste;

        return $this;
    }

    public function getDateEntree(): ?string
    {
        return $this->date_entree;
    }

    public function setDateEntree(string $date_entree): self
    {
        $this->date_entree = $date_entree;

        return $this;
    }
}
