<?php

namespace App\Controller\Admin;

use App\Entity\Covoiturage;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Text;
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
            AssociationField::new('voiture', 'Voiture'),
        ];
    }
    
}
