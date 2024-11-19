<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setEmail('user'.$i.'@gmail.com');
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            $user->setRoles(['ROLE_USER']);
            $user->setNom('nom '.$i);
            $user->setPrenom('prenom '.$i);
            $user->setTelephone('telephone '.$i);
            $user->setAdresse('adresse '.$i);
            $user->setPseudo('pseudo '.$i);
            $user->setVerified(true);

            // Générer une date de naissance aléatoire entre 18 et 60 ans
            $randomDate = new DateTimeImmutable();
            $randomDate->setTimestamp(rand(strtotime('-60 years'), strtotime('-18 years')));
            $user->setDateNaissance($randomDate);

            $user->setPhoto("https://picsum.photos/seed/user$i/200/200"); // URL de photo aléatoire

            $manager->persist($user);
        }

        $manager->flush();
    }
}
