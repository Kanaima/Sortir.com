<?php

namespace App\Entity;

use App\Repository\CampusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CampusRepository::class)
 */
class Campus
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
     *     min="2",
     *     max="80",
     *     minMessage="{{ limit }} characters min!",
     *     maxMessage="{{ limit }} characters max!")
     * @ORM\Column(type="string", nullable=false, unique=true)
     */
    private $nom;
    
    
    /**
     * One campus have many students
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="campus")
     */
    private $etudiants;
    
    
    /**
     * One campus organise many outings
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Sortie", mappedBy="campus")
     */
    private $sortiesOrganisees;
    
    /**
     * Campus constructor.
     */
    public function __construct()
    {
        $this->etudiants = new ArrayCollection();
        $this->sortiesOrganisees = new ArrayCollection();
    }
    
    
    
    //GETTERS ET SETTERS
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }
    
    /**
     * @param string $nom
     */
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }
    
    /**
     * @return ArrayCollection
     */
    public function getEtudiants(): ArrayCollection
    {
        return $this->etudiants;
    }
    
    /**
     * @param ArrayCollection $etudiants
     */
    public function setEtudiants(ArrayCollection $etudiants): void
    {
        $this->etudiants = $etudiants;
    }
    
    /**
     * @return ArrayCollection
     */
    public function getSortiesOrganisees(): ArrayCollection
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
    
    
    
}
