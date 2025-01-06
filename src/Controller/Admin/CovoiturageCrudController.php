<?php

namespace App\Controller\Admin;

use App\Entity\Covoiturage;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CovoiturageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Covoiturage::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            DateField::new('dateDepart'),
            TimeField::new('heureDepart'),
            TextField::new('lieuDepart'),
            DateField::new('dateArrivee'),
            TimeField::new('heureArrivee'),
            TextField::new('lieuArrivee'),
            TextField::new('statut'),
            IntegerField::new('nbPlace'),
            IntegerField::new('prixPersonne'),
            AssociationField::new('voiture', 'Voiture')
                ->setCrudController(VoitureCrudController::class)
                ->setFormTypeOptions([
                    'by_reference' => true,
                ])
        ];
    }

    /* public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Covoiturage) {
            $voiture = $entityInstance->getVoiture();
            if ($voiture && !$entityManager->contains($voiture)) {
                $entityManager->persist($voiture);
            }
        }

        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Covoiturage) {
            $voiture = $entityInstance->getVoiture();
            if ($voiture && !$entityManager->contains($voiture)) {
                $entityManager->persist($voiture);
            }
        }

        $entityManager->persist($entityInstance);
        $entityManager->flush();
    } */
    
}
