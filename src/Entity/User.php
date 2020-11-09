<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    
    /**
     * @var string
     * @Assert\Length (
     *     min="5",
     *     max="80",
     *     minMessage="Your pseudo must be at least {{ limit }} long!",
     *     maxMessage="Your pseudo is too long, {{ limit }} characters max.")
     * @ORM\Column (type="string", nullable=false, unique=true)
     */
    private $username;
    
    
    /**
     * @var string
     * @Assert\Email(message="{{ value }} is not a valid email.")
     * @ORM\Column(type="string", nullable=false, unique=true)
     */
    private $email;
    
    
    /**
     * @var string
     * @Assert\Regex(
     *     pattern="/(?=.[a-z]+)(?=.+[A-Z])(?=.+\d)(?=.+[\W\_])[A-Za-z\d\W\_]{8,30}/",
     *     htmlPattern="^(?=.[a-z]+)(?=.+[A-Z])(?=.+\d)(?=.+[\W\_])[A-Za-z\d\W\_]{8,30}$",
     *     message="Your password must contain at least 8 characters whose 1 uppercase, 1 lowercase, 1 number and
      1 special character.")
     * @Assert\Length (
     *     min="8",
     *     max="30",
     *     minMessage="{{ limit }} characters min!",
     *     maxMessage="{{ limit }} characters max!")
     * @ORM\Column (type="string", nullable=false, unique=true)
     */
    private $password;
    
    
    //pas d'annotations car pas stocké en bdd et il va renvoyer tjrs le même rôle
    private $roles;
    
    
    /**
     * @var DateTime
     * @Assert\LessThanOrEqual("now")
     * @ORM\Column(type="datetime")
     */
    private $dateCreated;
    
    
    //Customized
    
    /**
     * @var string
     * @Assert\Length (
     *     min="2",
     *     max="80",
     *     minMessage="{{ limit }} characters min!",
     *     maxMessage="{{ limit }} characters max!")
     * @ORM\Column(type="string", nullable=false)
     */
    private $name;
    
    
    /**
     * @var string
     * @Assert\Length (
     *     min="2",
     *     max="80",
     *     minMessage="{{ limit }} characters min!",
     *     maxMessage="{{ limit }} characters max!")
     * @ORM\Column(type="string", nullable=false)
     */
    private $firstName;
    
    
    /**
     * @var string
     * @Assert\Regex(
     *     pattern="/(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}/",
     *     htmlPattern="^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$")
     * @ORM\Column(type="string", nullable=false)
     */
    private $phoneNumber;
    
    
    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $admin;
    
    
    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $active;
    
    
    
    /**
     * Many participants have one campus
     * @var Campus
     * @ORM\ManyToOne(targetEntity="App\Entity\Campus", inversedBy="etudiants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $campus;
    
    
    /**
     * One participant organize many outings
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Sortie", mappedBy="organisateur")
     */
    private $sortiesOrganisees;
    
    
    /**
     * Many participants have many outings
     * @ORM\ManyToMany(targetEntity="App\Entity\Sortie", mappedBy="participants")
     */
    private $sorties;
    
    
    public function __construct()
    {
        $this->sortiesOrganisees = new ArrayCollection();
        $this->sorties = new ArrayCollection();
    }
    
    
    
    //GETTERS ET SETTERS
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }
    
    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }
    
    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
    
    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
    
    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
    
    /**
     * @return mixed
     */
    public function getRoles()
    {
        if ($this->isAdmin())
        {
            return ['ROLE_ADMIN'];
        }
        else
        {
            return ['ROLE_USER'];
        }
    } //pas de setter
    
   
    /**
     * @return DateTime
     */
    public function getDateCreated(): DateTime
    {
        return $this->dateCreated;
    }
    
    /**
     * @param DateTime $dateCreated
     */
    public function setDateCreated(DateTime $dateCreated): void
    {
        $this->dateCreated = $dateCreated;
    }
    
    //inutiles pour l'instant...
    public function getSalt()
    {
        return null;
    }
    
    public function eraseCredentials()
    {
        return null;
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    
    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }
    
    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }
    
    /**
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }
    
    /**
     * @param string $phoneNumber
     */
    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }
    
    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->admin;
    }
    
    /**
     * @param bool $admin
     */
    public function setAdmin(bool $admin): void
    {
        $this->admin = $admin;
    }
    
    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }
    
    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }
    
    /**
     * @return Campus
     */
    public function getCampus()
    {
        return $this->campus;
    }
    
    /**
     * @param Campus $campus
     */
    public function setCampus(Campus $campus): void
    {
        $this->campus = $campus;
    }
    
    /**
     * @return ArrayCollection
     */
    public function getSortiesOrganisees()
    {
        return $this->sortiesOrganisees;
    }
    
    /**
     * @param ArrayCollection $sortiesOrganisees
     */
    public function setSortiesOrganisees(ArrayCollection $sortiesOrganisees): void
    {
        $this->sortiesOrganisees = $sortiesOrganisees;
    }
    
    /**
     * @return ArrayCollection
     */
    public function getSorties()
    {
        return $this->sorties;
    }
    
    
    /**
     * @param Sortie $sortie
     * @return $this
     */
    public function addSortie(Sortie $sortie): self
    {
        if (!$this->sorties->contains($sortie))
        {
            $this->sorties->add($sortie);
        }
        return $this;
    }
    
    
    /**
     * @param Sortie $sortie
     * @return $this
     */
    public function removeSortie(Sortie $sortie): self
    {
        if ($this->sorties->contains($sortie))
        {
            $this->sorties->removeElement($sortie);
        }
        return $this;
    }
    
}
