<?php
namespace App\Form;

use App\Entity\User;
use App\Entity\Marque;
use App\Entity\Voiture;
use App\Entity\Covoiturage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class VoitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('modele', TextType::class, [
                'label' => 'Modèle'
            ])
            ->add('immatriculation', TextType::class, [
                'label' => 'Immatriculation'
            ])
            ->add('energie', TextType::class, [
                'label' => 'Énergie'
            ])
            ->add('couleur', TextType::class, [
                'label' => 'Couleur'
            ])
            ->add('date_premiere_immatriculation', DateType::class, [
                'label' => 'Date de première immatriculation',
                'widget' => 'single_text', // Utilisation d'un sélecteur de date
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nom', // Modifier selon votre entité User
                'label' => 'Propriétaire',
            ])
            ->add('marque', EntityType::class, [
                'class' => Marque::class,
                'choice_label' => 'libelle', // Modifier selon votre entité Marque
                'label' => 'Marque',
            ])
            ->add('covoiturages', EntityType::class, [
                'class' => Covoiturage::class,
                'choice_label' => 'lieu_arrivee', // Modifier selon votre entité Covoiturage
                'label' => 'Covoiturages',
                'multiple' => true,
                'expanded' => true, // Permet des cases à cocher
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }
}
