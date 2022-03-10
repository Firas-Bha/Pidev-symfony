<?php

namespace App\Entity;

use App\Repository\NutritionnisteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=NutritionnisteRepository::class)
 */
class Nutritionniste
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\NotBlank(message="champ obligatoir")
     */
    private $nom_nut;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="champ obligatoir")
     */
    private $PrenomNut;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="champ obligatoir")
     */
    private $adresseNut;

    /**
     * @ORM\Column(type="integer")
     *  @Assert\Positive
     */
    private $num_nut;

    /**
     * @ORM\OneToOne(targetEntity=Client::class, inversedBy="nutritionniste", cascade={"persist", "remove"})
      */
    private $Client;

   /* /**
     * @ORM\OneToOne(targetEntity=client::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="id_client",referencedColumnName="id_client")
     */
   /* private $client; */



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomNut(): ?string
    {
        return $this->nom_nut;
    }

    public function setNomNut(string $nom_nut): self
    {
        $this->nom_nut = $nom_nut;

        return $this;
    }

    public function getPrenomNut(): ?string
    {
        return $this->PrenomNut;
    }

    public function setPrenomNut(string $PrenomNut): self
    {
        $this->PrenomNut = $PrenomNut;

        return $this;
    }

    public function getAdresseNut(): ?string
    {
        return $this->adresseNut;
    }

    public function setAdresseNut(string $adresseNut): self
    {
        $this->adresseNut = $adresseNut;

        return $this;
    }

    public function getNumNut(): ?int
    {
        return $this->num_nut;
    }

    public function setNumNut(int $num_nut): self
    {
        $this->num_nut = $num_nut;

        return $this;
    }

    public function getClient(): ?client
    {
        return $this->client;
    }

    public function setClient(?client $client): self
    {
        $this->client = $client;

        return $this;
    }

}
