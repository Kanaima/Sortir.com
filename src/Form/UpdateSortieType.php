<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Repository\CampusRepository;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\VilleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateSortieType extends AbstractType
{
    
    private $campusRepository;
    private $villeRepository;
    private $lieuRepository;
    private $etatRepository;
    
    public function __construct(CampusRepository $campusRepository, VilleRepository $villeRepository, LieuRepository
    $lieuRepository, EtatRepository $etatRepository){
        $this->campusRepository = $campusRepository;
        $this->villeRepository = $villeRepository;
        $this->lieuRepository = $lieuRepository;
        $this->etatRepository = $etatRepository;
    }
    
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class,
                ['label'=> 'Nom de la sortie'])
            ->add('dateHeureDebut', DateTimeType::class,
                ['label'=>'Date et heure de la sortie', 'widget'=>'single_text'])
            ->add('dateLimiteInscription', DateTimeType::class,
                ['label'=>'Date limite d\'inscription', 'widget'=>'single_text'])
            ->add('nbInscriptionMax', NumberType::class,
                ['label'=>'Nombre de place'])
            ->add('duree',NumberType::class,
                ['label'=>'Durée', 'help'=>'en min.'])
            ->add('infosSortie', TextareaType::class,
                ['label'=>'Description et infos'])
            ->add('campus', EntityType::class,
                ['label'=>'Campus', 'class'=>Campus::class,
                    'choice_label'=>function(Campus $campus)
                    {
                        return sprintf($campus->getNom());
                    }, 'attr'=>['readonly'=>true]])
            ->add('ville', EntityType::class,
                ['mapped'=>false, 'class'=>Ville::class,
                    'choice_label'=>function(Ville $ville)
                    {
                        return sprintf($ville->getNom());
                    },
                    'choices'=>$this->villeRepository->findAll(),
                    'placeholder'=>'Sélectionner...'])
            ->add('lieu', EntityType::class,
                ['class'=>Lieu::class,
                    'choice_label'=>function(Lieu $lieu)
                    {
                        return sprintf($lieu->getNom());
                    },
                    'choices'=>$this->lieuRepository->findAll(),
                    'placeholder'=>'Sélectionner...'])
            ->add('codePostal',EntityType::class,
                ['mapped'=>false, 'attr'=>['readonly'=>true], 'class'=>Ville::class,
                    'choice_label'=>function(Ville $ville)
                    {
                        return sprintf($ville->getCodePostal());
                    }])
            ->add('etat',EntityType::class,
                ['class'=>Etat::class,
                    'choice_label'=>function(Etat $etat)
                    {
                        return sprintf($etat->getLibelle());
                    },
                    'choices'=>$this->etatRepository->findAll(),
                    'help'=>'choisir \'Ouverte\' pour publier']);
        
        
        
        $formModifier = function (FormInterface $form, Lieu $lieu = null)
        {
            $rue = null === $lieu ? [] : $lieu->getRue();
            $latitude = null === $lieu ? [] : $lieu->getLatitude();
            $longitude = null === $lieu ? [] : $lieu->getLongitude();
    
            $form->add('rue',TextType::class,
                ['mapped'=>false, 'attr'=>['disabled'=>true], 'data'=>$rue ])
                ->add('latitude',TextType::class,
                    ['mapped'=>false, 'attr'=>['disabled'=>true], 'data'=>$latitude])
                ->add('longitude',TextType::class,
                    ['mapped'=>false, 'attr'=>['disabled'=>true], 'data'=>$longitude])
            ;
    
        };
        
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($formModifier)
        {
            $data = $event->getData();
            $formModifier($event->getForm(), $data->getLieu());
        });
        
        
        $builder->get('lieu')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($formModifier)
        {
            // It's important here to fetch $event->getForm()->getData(), as
            // $event->getData() will get the client data (that is, the ID)
            $lieu = $event->getForm()->getData();
    
            // since we've added the listener to the child, we'll have to pass on
            // the parent to the callback functions!
            $formModifier($event->getForm()->getParent(), $lieu);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
