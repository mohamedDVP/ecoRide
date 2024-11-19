<?php

namespace App\DataFixtures;

use App\Entity\Covoiturage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CovoiturageFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; $i++) {
            $covoiturage = new Covoiturage();
            $covoiturage->setNbPlace(random_int(1, 4));
            $covoiturage->setVilleDepart('villeDepart '.$i);
            $covoiturage->setNom('nom '.$i);
            $covoiturage->setPrenom('prenom '.$i);
            $covoiturage->setTelephone('telephone '.$i);
            $covoiturage->setAdresse('adresse '.$i);
            $covoiturage->setPseudo('pseudo '.$i);
            $user->setVerified(true);

            // Générer une date de naissance aléatoire entre 18 et 60 ans
            $randomDate = new DateTimeImmutable();
            $randomDate->setTimestamp(rand(strtotime('-60 years'), strtotime('-18 years')));
            $covoiturage->setDateNaissance($randomDate);

            $covoiturage->setPhoto("https://picsum.photos/seed/user$i/200/200"); // URL de photo aléatoire

            $manager->persist($user);
        }


        $manager->flush();
    }
}
