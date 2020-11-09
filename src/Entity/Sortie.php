<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContext;

/**
 * @ORM\Entity(repositoryClass=SortieRepository::class)
 */
class Sortie
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
     *     minMessage="{{ limit }} characters min!",
     *     maxMessage="{{ limit }} characters max!")
     * @ORM\Column(type="string", nullable=false)
     */
    private $nom;
    
    
    /**
     * @var DateTime
     * @Assert\GreaterThanOrEqual(
     *     "+72 hours",
     *     message="Your activity can't start before {{ compared_value }} hours")
     * @ORM\Column(type="datetime")
     */
    private $dateHeureDebut;
    
    
    /**
     * @var integer
     * @Assert\Positive()
     * @ORM\Column(type="integer", nullable=false)
     */
    private $duree;
    
    
    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $dateLimiteInscription;
    
    
    /**
     * @var integer
     * @Assert\NotBlank()
     * @ORM\Column(type="integer", nullable=false, length=4)
     */
    private $nbInscriptionMax;
    
    
    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(type="string", nullable=false, length=255)
     */
    private $infosSortie;
    
    
    /**
     * Many outings have one state
     * @var Etat
     * @ORM\ManyToOne(targetEntity="App\Entity\Etat", inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $etat;
    
    
    /**
     * Many outings have one organizer
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="sortiesOrganisees")
     * @ORM\JoinColumn(nullable=false)
     */
    private $organisateur;
    
    
    /**
     * Many outings have many participants
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="sorties")
     */
    private $participants;
    
    
    /**
     * Many outings have one campus
     * @var Campus
     * @ORM\ManyToOne(targetEntity="App\Entity\Campus", inversedBy="sortiesOrganisees")
     * @ORM\JoinColumn(nullable=false)
     */
    private $campus;
    
    
    /**
     * Many outings have one place
     * @var Lieu
     * @ORM\ManyToOne(targetEntity="App\Entity\Lieu",inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lieu;

    
    public function __construct()
    {
        $this->participants = new ArrayCollection();
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
     * @return DateTime
     */
    public function getDateHeureDebut()
    {
        return $this->dateHeureDebut;
    }
    
    /**
     * @param DateTime $dateHeureDebut
     */
    public function setDateHeureDebut(DateTime $dateHeureDebut): void
    {
        $this->dateHeureDebut = $dateHeureDebut;
    }
    
    /**
     * @return int
     */
    public function getDuree()
    {
        return $this->duree;
    }
    
    /**
     * @param int $duree
     */
    public function setDuree(int $duree): void
    {
        $this->duree = $duree;
    }
    
    /**
     * @return DateTime
     */
    public function getDateLimiteInscription()
    {
        return $this->dateLimiteInscription;
    }
    
    /**
     * @param DateTime $dateLimiteInscription
     */
    public function setDateLimiteInscription(DateTime $dateLimiteInscription): void
    {
        $this->dateLimiteInscription = $dateLimiteInscription;
    }
    
    /**
     * @return int
     */
    public function getNbInscriptionMax()
    {
        return $this->nbInscriptionMax;
    }
    
    /**
     * @param int $nbInscriptionMax
     */
    public function setNbInscriptionMax(int $nbInscriptionMax): void
    {
        $this->nbInscriptionMax = $nbInscriptionMax;
    }
    
    /**
     * @return string
     */
    public function getInfosSortie()
    {
        return $this->infosSortie;
    }
    
    /**
     * @param string $infosSortie
     */
    public function setInfosSortie(string $infosSortie): void
    {
        $this->infosSortie = $infosSortie;
    }
    
    /**
     * @return Etat
     */
    public function getEtat()
    {
        return $this->etat;
    }
    
    /**
     * @param Etat $etat
     */
    public function setEtat(Etat $etat): void
    {
        $this->etat = $etat;
    }
    
    /**
     * @return User
     */
    public function getOrganisateur()
    {
        return $this->organisateur;
    }
    
    /**
     * @param User $organisateur
     */
    public function setOrganisateur(User $organisateur): void
    {
        $this->organisateur = $organisateur;
    }
    
    
    
    /**
     * @return ArrayCollection
     */
    public function getParticipants()
    {
        return $this->participants;
    }
    
    
    /**
     * @param User $participant
     * @return $this
     */
    public function addParticipant(User $participant): self
    {
        if (!$this->participants->contains($participant))
        {
            $this->participants[] = $participant;
        }
        return $this;
    }
    
    
    /**
     * @param User $participant
     * @return $this
     */
    public function removeParticipant(User $participant): self
    {
        if ($this->participants->contains($participant))
        {
            $this->participants->removeElement($participant);
        }
        return $this;
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
     * @return Lieu
     */
    public function getLieu()
    {
        return $this->lieu;
    }
    
    /**
     * @param Lieu $lieu
     */
    public function setLieu(Lieu $lieu): void
    {
        $this->lieu = $lieu;
    }
    
    
    //Callback de validation dateLimiteInscription
    
    public function validateDateLimite(ExecutionContext $context)
    {
        $dateLimiteValide = date_sub($this->getDateHeureDebut
            (),date_interval_create_from_date_string('1 days'));
        
        if ($this->getDateLimiteInscription()>$dateLimiteValide)
        {
            $context->buildViolation('This must be at least 1 day before the activity')
                ->atPath('dateLimiteInscription')
                ->addViolation();
        }
        
    }
    
    
}
