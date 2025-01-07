<?php
namespace App\Repository;

use App\Entity\Covoiturage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CovoiturageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Covoiturage::class);
    }

    public function findByFilters($lieuDepart, $lieuArrivee, $dateDepart, $dateArrivee, $heureDepart, $heureArrivee, $statut, $nbPlace, $prixPersonne)  // Ajoutez les paramètres nécessaires
    {
        $qb = $this->createQueryBuilder('c');

        if ($lieuDepart) {
            $qb->andWhere('c.lieu_depart LIKE :lieu_depart')
               ->setParameter('lieu_depart', '%' . $lieuDepart . '%');
        }

        if ($lieuArrivee) {
            $qb->andWhere('c.lieu_arrivee LIKE :lieu_arrivee')
               ->setParameter('lieu_arrivee', '%' . $lieuArrivee . '%');
        }

        if ($dateDepart) {
            $qb->andWhere('c.date_depart = :date_depart')
               ->setParameter('date_depart', $dateDepart);
        }

        if ($dateArrivee) {
            $qb->andWhere('c.date_arrivee = :date_arrivee')
               ->setParameter('date_arrivee', $dateArrivee);
        }

        if ($heureDepart) {
            $qb->andWhere('c.heure_depart = :heure_depart')
               ->setParameter('heure_depart', $heureDepart);
        }

        if ($heureArrivee) {
            $qb->andWhere('c.heure_arrivee = :heure_arrivee')
               ->setParameter('heure_arrivee', $heureArrivee);
        }

        if ($statut) {
            $qb->andWhere('c.statut = :statut')
               ->setParameter('statut', $statut);
        }

        if ($nbPlace) {
            $qb->andWhere('c.nb_place = :nb_place')
               ->setParameter('nb_place', $nbPlace);
        }

        if ($prixPersonne) {
            $qb->andWhere('c.prix_personne = :prix_personne')
               ->setParameter('prix_personne', $prixPersonne);
        }

        return $qb->getQuery()->getResult();
    }
}
