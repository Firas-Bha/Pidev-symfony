<?php

namespace App\Entity;
use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @ORM\Entity(repositoryClass=EvenementRepository::class)
 */
class Evenement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Veullez inserer le nom")
     * @ORM\Column(type="string", length=255)
     */
    private $Nom;

    /**
     * @Assert\NotBlank(message="Veullez inserer la capacité")
     * @ORM\Column(type="integer")
     */
    private $Capacite;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="date")
     */
    private $Date;

    /**
     * @Assert\NotBlank
     * (message="Veullez inserer une description")
     * @ORM\Column(type="string", length=255)
     */
    private $Description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Image;

    /**
     * @Assert\NotBlank
     * (message="Veullez inserer une Adresse")
     * @ORM\Column(type="string", length=255)
     */
    private $Adresse;

    /**
     * @ORM\OneToMany(targetEntity=Calendar::class, mappedBy="evenement")
     */
    private $calendar;

    public function __construct()
    {
        $this->calendar = new ArrayCollection();
    }


    /**
     * @return mixed
     */
    public function getid()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setid($id): void
    {
        $this->id = $id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->Capacite;
    }

    public function setCapacite(int $Capacite): self
    {
        $this->Capacite = $Capacite;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->Image;
    }

    public function setImage(string $Image)
    {
        $this->Image = $Image;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->Adresse;
    }

    public function setAdresse(string $Adresse): self
    {
        $this->Adresse = $Adresse;

        return $this;
    }

    /**
     * @return Collection|Calendar[]
     */
    public function getCalendar(): Collection
    {
        return $this->calendar;
    }

    public function addCalendar(Calendar $calendar): self
    {
        if (!$this->calendar->contains($calendar)) {
            $this->calendar[] = $calendar;
            $calendar->setEvenement($this);
        }

        return $this;
    }

    public function removeCalendar(Calendar $calendar): self
    {
        if ($this->calendar->removeElement($calendar)) {
            // set the owning side to null (unless already changed)
            if ($calendar->getEvenement() === $this) {
                $calendar->setEvenement(null);
            }
        }

        return $this;
    }



}
