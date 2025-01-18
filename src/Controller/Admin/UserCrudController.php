<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
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
            TextField::new("email")->setLabel('Email'),
            AssociationField::new('role')
                ->setLabel('Rôles')
                ->setCrudController(RoleCrudController::class)
                ->setFormTypeOptions([
                    'by_reference' => false,
                    'multiple' => true,
                ]),
            TextField::new('plainPassword')
                ->setFormType(RepeatedType::class)
                ->setFormTypeOptions([
                    'type' => PasswordType::class,
                    'first_options' => ['label' => 'Mot de passe'],
                    'second_options' => ['label' => 'Répétez le mot de passe'],
                    'mapped' => false, // Indique que ce champ n'est pas directement lié à l'entité User
                ])
                ->onlyOnForms(),
            ImageField::new('photo')
                ->setBasePath('uploads/user')
                ->setUploadDir('public/uploads/user')
                ->setRequired(false)
                ->setLabel('Photo de profil'),
            TextField::new('nom')->setLabel('Nom'),
            TextField::new('prenom')->setLabel('Prénom'),
            TelephoneField::new('telephone')->setLabel('Téléphone'),
            TextField::new('adresse')->setLabel('Adresse'),
            TextField::new('pseudo')->setLabel('Pseudo'),
            DateField::new('dateNaissance')->setLabel('Date de naissance'),
            BooleanField::new('isVerified')
                ->setLabel('Compte vérifié'),
        ];
    }

        public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
        {
            $this->handlePassword($entityInstance);
            dump($entityInstance);
            parent::persistEntity($entityManager, $entityInstance);
        }

        public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
        {
            $this->handlePassword($entityInstance);
            parent::updateEntity($entityManager, $entityInstance);
        }

        private function handlePassword(User $user): void
        {
            // Vérifier si le mot de passe est défini dans le formulaire
            if ($user->getPlainPassword()) {
                // Si un mot de passe est fourni, on le hache
                $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPlainPassword());
                $user->setPassword($hashedPassword);
                $user->eraseCredentials(); // Supprime les informations sensibles
            } elseif (!$user->getPassword()) {
                // Si aucun mot de passe n'est fourni, on lève une exception pour signaler un problème
                throw new \Exception("Le mot de passe est requis.");
            }
        }
}
