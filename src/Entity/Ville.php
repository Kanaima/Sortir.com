<?php

namespace App\Entity;

use App\Repository\VilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=VilleRepository::class)
 */
class Ville
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    
    /**
     * @var string
     * @Assert\Length(
     *     max="80",
     *     maxMessage="{{ limit }} characters max!"
     * )
     * @ORM\Column (type="string", nullable=false, unique=true)
     */
    private $nom;
    
    
    /**
     * @var string
     * @Assert\Regex(
     *     pattern="/\d.{5}/",
     *     htmlPattern="^\d.{5}$")
     * @ORM\Column (type="string", nullable=false, unique=true)
     */
    private $codePostal;
    
    
    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Lieu", mappedBy="ville")
     */
    private $lieux;
    
    
    public function __construct()
    {
        $this->lieux = new ArrayCollection();
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
    public function getCodePostal()
    {
        return $this->codePostal;
    }
    
    /**
     * @param string $codePostal
     */
    public function setCodePostal(string $codePostal): void
    {
        $this->codePostal = $codePostal;
    }
    
    /**
     * @return ArrayCollection
     */
    public function getLieux(): ArrayCollection
    {
        return $this->lieux;
    }
    
    /**
     * @param ArrayCollection $lieux
     */
    public function setLieux(ArrayCollection $lieux): void
    {
        $this->lieux = $lieux;
    }
    
    
    
}
