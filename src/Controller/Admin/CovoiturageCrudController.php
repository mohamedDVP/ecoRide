<?php

namespace App\Controller\Admin;

use App\Entity\Covoiturage;
use Doctrine\ORM\EntityManagerInterface;
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
            AssociationField::new('voiture')
                ->setLabel('Voiture')
                ->setFormTypeOptions([
                    'by_reference' => false, // Pour éviter que la relation ne soit en lecture seule
                    'query_builder' => function($repo) {
                        return $repo->createQueryBuilder('v')
                            ->orderBy('v.modele', 'ASC');
                    },
                    'mapped' => true,
                ])
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Covoiturage) {
            $voiture = $entityInstance->getVoiture();
            if ($voiture && !$entityManager->contains($voiture)) {
                $entityManager->persist($voiture); // Persister l'entité associée
            }
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Covoiturage) {
            $voiture = $entityInstance->getVoiture();
            if ($voiture === null) {
                throw new \LogicException('Impossible de modifier le covoiturage sans voiture.');
            }
        }

        parent::updateEntity($entityManager, $entityInstance);
    }

    
}
