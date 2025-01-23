<?php

namespace App\Controller\Admin;

use App\Entity\Avis;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AvisCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Avis::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            
            TextEditorField::new('commentaire')
                ->setLabel('Commentaire'),
            IntegerField::new('note')
                ->setLabel('Note'),
            TextField::new('statut')
                ->setLabel('Statut'),
            DateTimeField::new('publishedAt')
                ->setLabel('Publié le')
                ->setFormat('dd-MM-yyyy HH:mm:ss'),
            AssociationField::new('users')
                ->setLabel('Utilisateur')
                ->setCrudController(UserCrudController::class)
                ->setFormTypeOptions([
                    'by_reference' => false,
                    'multiple' => true,
                ]),
        ];
    }
    
}
