<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Marque;
use App\Entity\Voiture;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class VoitureCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Voiture::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('modele'),
            TextField::new('immatriculation'),
            TextField::new('energie'),
            TextField::new('couleur'),
            DateField::new('date_premiere_immatriculation'),
            CollectionField::new(User::class, 'user'),
            CollectionField::new(Marque::class,'Marque'),
        ];
    }
    
}
