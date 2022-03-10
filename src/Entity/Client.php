<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
//use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
//use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 * @UniqueEntity(
 *     fields={"username"},
 *     message="Le username que vous avez indiqué est déjà utilisé!"
 *     )
 */
class Client implements UserInterface/*, PasswordAuthenticatedUserInterface*/
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     *
     * @ORM\Column(type="string")
     * @Assert\Length (min="8", minMessage="votre mot de passe doit faire 8 caractéres")
     *  @Assert\EqualTo(propertyPath="confirm_password",message="Vous n'avez pas tapé le même mot de passe")
     */
    private $password;

    protected $captchaCode;

    /**
     * @Assert\EqualTo(propertyPath="password",message="Vous n'avez pas tapé le même mot de passe")
     */
    public $confirm_password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom_c;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom_c;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $date_de_naiss_c;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sexe_c;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse_c;

    /**
     * @ORM\Column(type="integer")
     */
    private $telephone_c;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email_c;

    /**
     * @ORM\OneToMany(targetEntity=Coach::class, mappedBy="Client")
     */
    private $coaches;

    /**
     * @ORM\OneToOne(targetEntity=Nutritionniste::class, mappedBy="Client", cascade={"persist", "remove"})
     */
    private $nutritionniste;

    public function __construct()
    {
        $this->coaches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }
    /**
     * The public representation of the user (e.g. a username, an email address, etc.)
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */

    public function getRoles(): array
    {
        $roles = $this->roles;

        // il est obligatoire d'avoir au moins un rôle si on est authentifié, par convention c'est ROLE_USER
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     *  @see PasswordAuthenticatedUserInterface
     *
     */
    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNomC(): ?string
    {
        return $this->nom_c;
    }

    public function setNomC(string $nom_c): self
    {
        $this->nom_c = $nom_c;

        return $this;
    }

    public function getPrenomC(): ?string
    {
        return $this->prenom_c;
    }

    public function setPrenomC(string $prenom_c): self
    {
        $this->prenom_c = $prenom_c;

        return $this;
    }

    public function getDateDeNaissC(): ?string
    {
        return $this->date_de_naiss_c;
    }

    public function setDateDeNaissC(string $date_de_naiss_c): self
    {
        $this->date_de_naiss_c = $date_de_naiss_c;

        return $this;
    }

    public function getSexeC(): ?string
    {
        return $this->sexe_c;
    }

    public function setSexeC(string $sexe_c): self
    {
        $this->sexe_c = $sexe_c;

        return $this;
    }

    public function getAdresseC(): ?string
    {
        return $this->adresse_c;
    }

    public function setAdresseC(string $adresse_c): self
    {
        $this->adresse_c = $adresse_c;

        return $this;
    }

    public function getTelephoneC(): ?int
    {
        return $this->telephone_c;
    }

    public function setTelephoneC(int $telephone_c): self
    {
        $this->telephone_c = $telephone_c;

        return $this;
    }

    public function getEmailC(): ?string
    {
        return $this->email_c;
    }

    public function setEmailC(string $email_c): self
    {
        $this->email_c = $email_c;

        return $this;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }
    public function getCaptchaCode()
    {
        return $this->captchaCode;
    }
    public function setCaptchaCode($captchaCode)
    {
         $this->captchaCode=$captchaCode;
         return $this;
    }

    /**
     * @return Collection<int, Coach>
     */
    public function getCoaches(): Collection
    {
        return $this->coaches;
    }

    public function addCoach(Coach $coach): self
    {
        if (!$this->coaches->contains($coach)) {
            $this->coaches[] = $coach;
            $coach->setClient($this);
        }

        return $this;
    }

    public function removeCoach(Coach $coach): self
    {
        if ($this->coaches->removeElement($coach)) {
            // set the owning side to null (unless already changed)
            if ($coach->getClient() === $this) {
                $coach->setClient(null);
            }
        }

        return $this;
    }

    public function getNutritionniste(): ?Nutritionniste
    {
        return $this->nutritionniste;
    }

    public function setNutritionniste(?Nutritionniste $nutritionniste): self
    {
        // unset the owning side of the relation if necessary
        if ($nutritionniste === null && $this->nutritionniste !== null) {
            $this->nutritionniste->setClient(null);
        }

        // set the owning side of the relation if necessary
        if ($nutritionniste !== null && $nutritionniste->getClient() !== $this) {
            $nutritionniste->setClient($this);
        }

        $this->nutritionniste = $nutritionniste;

        return $this;
    }
}
