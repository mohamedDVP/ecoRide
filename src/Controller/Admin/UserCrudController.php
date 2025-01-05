<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\PasswordField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new("email"),
            AssociationField::new("role")
                ->setLabel("Rôles")
                ->setFormTypeOptions([
                    'by_reference' => false,
                    'mapped'=> true,
                    'multiple' => true,
                ])
                ->setCrudController(RoleCrudController::class),
            TextField::new('plainPassword')
                ->setFormType(RepeatedType::class)
                ->setFormTypeOptions([
                    'type' => PasswordType::class,
                    'first_options' => ['label' => 'Mot de passe'],
                    'second_options' => ['label' => 'Répétez le mot de passe'],
                ])
                ->onlyOnForms(),
            ImageField::new('photo')
                ->setBasePath('public/uploads/user')
                ->setUploadDir('public/uploads/user')
                ->setRequired(false)
                ->setLabel('Photo de profil')
                ->setFormTypeOptions([
                    'mapped'=> true,
                ]),
            TextField::new('nom'),
            TextField::new('prenom'),
            TelephoneField::new('telephone'),
            TextField::new('adresse'),
            TextField::new('pseudo'),
            DateField::new('dateNaissance'),
            
            BooleanField::new('isVerified')
                ->setLabel('Compte vérifié')
                ->setFormTypeOptions([
                    'mapped'=> true,
            ]),
        ];
    }

        public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->handlePassword($entityInstance);
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->handlePassword($entityInstance);
        parent::updateEntity($entityManager, $entityInstance);
    }

    private function handlePassword(User $user): void
{
    if ($user->getPlainPassword()) {
        $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPlainPassword());
        $user->setPassword($hashedPassword);
        $user->eraseCredentials(); // Efface le mot de passe en clair après le hachage
    }
}
}
