<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Ville;
use App\Repository\VilleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuType extends AbstractType
{
    private $villeRepository;
    
    public function __construct(VilleRepository $villeRepository)
    {
        $this->villeRepository = $villeRepository;
    }
    
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class,
                ['label'=> 'Nom du lieu:', 'help'=>'(nom du cinéma, du stade, de la place...)'])
            ->add('rue', TextType::class,
                ['label'=> 'Rue :'])
            ->add('latitude', NumberType::class, ['label'=> 'Latitude :', 'help'=>'between -90.000000 and 90.00000',
                'scale'=>6, 'attr'=>['min'=>-90, 'max'=>90]])
            ->add('longitude', NumberType::class, ['label'=> 'Longitude :', 'help'=>'between -180.000000 and 180.00000',
                'scale'=>6, 'attr'=>['min'=>-180, 'max'=>180]])
            ->add('ville', EntityType::class,
                ['label'=>'Ville :', 'class'=>Ville::class, 'choice_label'=>function(Ville $ville)
                {
                    return $ville->getNom();
                },
                'choices'=>$this->villeRepository->findAll(),
                'placeholder'=>'Sélectionner...'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
