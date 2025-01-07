<?php

namespace App\Form;

use App\Entity\Covoiturage;
use App\Entity\User;
use App\Entity\Voiture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CovoiturageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDepart', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de départ',
                'html5' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('heureDepart', TimeType::class, [
                'widget' => 'single_text',
                'label' => 'Heure de départ',
                'html5' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('lieuDepart', TextType::class, [
                'label' => 'Lieu de départ',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('dateArrivee', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date d’arrivée',
                'html5' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('heureArrivee', TimeType::class, [
                'widget' => 'single_text',
                'label' => 'Heure d’arrivée',
                'html5' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('lieuArrivee', TextType::class, [
                'label' => 'Lieu d’arrivée',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('statut', TextType::class, [
                'label' => 'Statut du covoiturage',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('nbPlace', IntegerType::class, [
                'label' => 'Nombre de places disponibles',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('prixPersonne', MoneyType::class, [
                'label' => 'Prix par personne',
                'currency' => 'EUR',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('voiture', EntityType::class, [
                'class' => Voiture::class,
                'choice_label' => 'modele',
                'label' => 'Voiture utilisée',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('users', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'pseudo',
                'label' => 'Participants',
                'multiple' => true,
                'expanded' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Covoiturage::class,
        ]);
    }
}
