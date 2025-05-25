<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use Symfony\Component\Validator\Constraints\GroupSequence;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $passwordHasher;
    private ValidatorInterface $validator;


    public function __construct(UserPasswordHasherInterface $passwordHasher, ValidatorInterface $validator)
    {
        $this->passwordHasher = $passwordHasher;
        $this->validator = $validator;
    }
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // Hachage du mot de passe avant la persistance
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
        // Vérifiez si un mot de passe est fourni
        if ($user->getPlainPassword()) {
            // Valider le mot de passe
            $errors = $this->validator->validate($user, null);

            // Si des erreurs existent, les afficher
            if (count($errors) > 0) {
                $errorMessage = '';
                foreach ($errors as $error) {
                    $errorMessage .= $error->getPropertyPath() . ': ' . $error->getMessage() . "\n";
                }
                throw new \RuntimeException($errorMessage);
            }
            // Hachage du mot de passe si tout est valide
            $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPlainPassword());
            $user->setPassword($hashedPassword);
            $user->eraseCredentials(); // Efface le plainPassword pour éviter les fuites
        } else {
            // Si aucun mot de passe n'est fourni, lever une exception pour indiquer que le mot de passe est requis
            throw new \Exception("Le mot de passe est requis.");
        }
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new("email")->setLabel('Email'),
            AssociationField::new('roleEntities') // Utilise 'role' pour la relation ManyToMany avec Role
                ->setCrudController(RoleCrudController::class) // Assurez-vous que RoleCrudController est défini
                ->setFormTypeOptions([
                    'choice_label' => 'libelle', // Utilisez 'libelle' si c'est le nom du rôle
                    'by_reference' => false,  // Important, il faut éviter le passage par référence
                    'multiple' => true, // Permet la sélection multiple
                ])
                ->setLabel('Rôles'),
            TextField::new('plainPassword')
                ->setFormType(RepeatedType::class)
                ->setFormTypeOptions([
                    'type' => PasswordType::class,
                    'first_options' => ['label' => 'Mot de passe'],
                    'second_options' => ['label' => 'Confirmer le mot de passe'],
                    //'mapped' => false, // Indique que ce champ n'est pas lié à Doctrine
                    'required' => $pageName === Crud::PAGE_NEW, // Requis uniquement pour l'ajout
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


}