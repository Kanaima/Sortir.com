<?php

namespace App\Entity;

use App\Repository\LieuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LieuRepository::class)
 */
class Lieu
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    
    /**
     * @var string
     * @Assert\Unique(message="{{ value }} existe déjà. Vérifier la liste de proposition ou ajouter une précision.")
     * @Assert\Length (
     *     min="2",
     *     max="80",
     *     minMessage="{{ limit }} characters min!",
     *     maxMessage="{{ limit }} characters max!")
     * @ORM\Column (type="string", nullable=false, unique=true)
     */
    private $nom;
    
    
    /**
     * @var string
     * @Assert\Length (
     *     min="2",
     *     max="80",
     *     minMessage="{{ limit }} characters min!",
     *     maxMessage="{{ limit }} characters max!")
     * @ORM\Column (type="string", nullable=false)
     */
    private $rue;
    
    
    /**
     * @var float
     * @Assert\LessThanOrEqual(90)
     * @Assert\GreaterThanOrEqual(-90)
     * @Assert\Regex(
     *     pattern="/\d.{0,3}(\.\d+)/",
     *     htmlPattern="^\d.{0,3}(\.\d+)$")
     * @ORM\Column (type="float", scale=6)
     */
    private $latitude;
    
    
    /**
     * @var float
     * @Assert\LessThanOrEqual(180)
     * @Assert\GreaterThanOrEqual(-180)
     * @Assert\Regex(
     *     pattern="/\d.{0,3}(\.\d+)/",
     *     htmlPattern="^\d.{0,3}(\.\d+)$")
     * @ORM\Column (type="float", scale=6)
     */
    private $longitude;
    
    
    /**
     * Many place have one city
     * @var Ville
     * @ORM\ManyToOne(targetEntity="App\Entity\Ville", inversedBy="lieux")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ville;
    
    
    /**
     * One place have many outings
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Sortie", mappedBy="lieu")
     */
    private $sorties;
    
    
    public function __construct()
    {
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
     * @return string
     */
    public function getRue()
    {
        return $this->rue;
    }
    
    /**
     * @param string $rue
     */
    public function setRue(string $rue): void
    {
        $this->rue = $rue;
    }
    
    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }
    
    /**
     * @param float $latitude
     */
    public function setLatitude(float $latitude): void
    {
        $this->latitude = $latitude;
    }
    
    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }
    
    /**
     * @param float $longitude
     */
    public function setLongitude(float $longitude): void
    {
        $this->longitude = $longitude;
    }
    
    /**
     * @return Ville
     */
    public function getVille()
    {
        return $this->ville;
    }
    
    /**
     * @param Ville $ville
     */
    public function setVille(Ville $ville): void
    {
        $this->ville = $ville;
    }
    
    /**
     * @return ArrayCollection
     */
    public function getSorties(): ArrayCollection
    {
        return $this->sorties;
    }
    
    /**
     * @param ArrayCollection $sorties
     */
    public function setSorties(ArrayCollection $sorties): void
    {
        $this->sorties = $sorties;
    }
    
    
    
}
