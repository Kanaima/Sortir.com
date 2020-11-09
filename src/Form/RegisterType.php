<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\User;
use App\Repository\CampusRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class RegisterType extends AbstractType
{
    
    private $campusRepository;
    
    public function __construct(CampusRepository $campusRepository)
    {
        $this->campusRepository = $campusRepository;
    }
    
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class,
                ['attr'=>['placeholder' =>'vanessa46'],
                    'required' => true, 'label'=> 'Pseudo'])
            ->add('email',EmailType::class,
                ['attr'=>['placeholder' =>'vanessa46@gmail.com'],
                    'required' => true, 'label'=> 'Email'])
            ->add('password', RepeatedType::class,
                ['type' => PasswordType::class,
                    'invalid_message' => 'The password fields must match.',
                    'required' => true,
                    'first_options'  => ['label' => 'Mot de passe'],
                    'second_options' => ['label' => 'Confirmation mot de passe'],
                    'constraints'=> [new Regex('/(?=.[a-z]+)(?=.+[A-Z])(?=.+\d)(?=.+[\W\_])[A-Za-z\d\W\_]{8,30}/')]
                ])
            ->add('name', TextType::class,
                ['attr'=>['placeholder' =>'example: Caplan'],
                    'required' => true, 'label'=> 'Nom'])
            ->add('firstName', TextType::class,
                ['attr'=>['placeholder' =>'Vanessa'],
                    'required' => true, 'label'=> 'Prénom'])
            ->add('phoneNumber', TextType::class,
                ['attr'=>['placeholder' =>'06 XX XX XX XX'],
                    'required' => true, 'constraints' => [new Regex('/(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}/')],
                    'label'=> 'Tel'])
            ->add('active', CheckboxType::class, ['label'=> 'Actif'])
            ->add('campus', EntityType::class,
                ['class'=>Campus::class,
                    'choice_label' => function(Campus $campus)
                    {
                        return sprintf($campus->getNom());
                    },
                    'choices'=> $this->campusRepository->findAll(),
                    'placeholder'=>'Sélectionner...'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //association de mon formulaire et mon entité
            'data_class' => User::class,
        ]);
    }
}
