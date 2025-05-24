<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CovoiturageRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    #[IsGranted('ROLE_USER')] // Vérifie que l'utilisateur est connecté
    public function index(#[CurrentUser] ?User $user): Response
    {
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/profil/devenir-conducteur', name: 'app_devenir_conducteur', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function devenirConducteur(EntityManagerInterface $em, RoleRepository $roleRepo): Response
    {
        $user = $this->getUser();

        if (!$user || !$user instanceof User) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour effectuer cette action.');
        }

        $roleConducteur = $roleRepo->findOneBy(['libelle' => 'ROLE_CONDUCTEUR']);
        $rolePassager = $roleRepo->findOneBy(['libelle' => 'ROLE_PASSAGER']);

        if (!$roleConducteur) {
            throw $this->createNotFoundException("Le rôle 'ROLE_CONDUCTEUR' n'existe pas en base.");
        }

        // Supprimer le rôle passager s’il est présent
        if ($rolePassager && $user->getRoleEntities()->contains($rolePassager)) {
            $user->removeRole($rolePassager);
        }

        // Ajouter le rôle conducteur s’il n’est pas déjà présent
        if (!$user->getRoleEntities()->contains($roleConducteur)) {
            $user->addRole($roleConducteur);
        }

        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('app_profile');
    }


    #[Route('/profil/devenir-passager', name: 'app_devenir_passager', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function devenirPassager(EntityManagerInterface $em, RoleRepository $roleRepo): Response
    {
        $user = $this->getUser();

        if (!$user || !$user instanceof User) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour effectuer cette action.');
        }

        $rolePassager = $roleRepo->findOneBy(['libelle' => 'ROLE_PASSAGER']);
        $roleConducteur = $roleRepo->findOneBy(['libelle' => 'ROLE_CONDUCTEUR']);

        if (!$rolePassager) {
            throw $this->createNotFoundException("Le rôle 'ROLE_PASSAGER' n'existe pas en base.");
        }

        // Supprimer le rôle conducteur s’il est présent
        if ($roleConducteur && $user->getRoleEntities()->contains($roleConducteur)) {
            $user->removeRole($roleConducteur);
        }

        // Ajouter le rôle passager s’il n’est pas déjà présent
        if (!$user->getRoleEntities()->contains($rolePassager)) {
            $user->addRole($rolePassager);
        }

        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('app_profile');
    }



    #[Route('/historique', name: 'app_profil_historique')]
    #[IsGranted('ROLE_USER')] // Seuls les utilisateurs connectés peuvent voir leur historique
    public function historique(CovoiturageRepository $covoiturageRepository, UserInterface $user): Response
    {
        // Récupérer l'historique des covoiturages de l'utilisateur connecté
        $covoiturages = $covoiturageRepository->createQueryBuilder('c')
            ->innerJoin('c.users', 'u')
            ->where('u = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();

        return $this->render('profile/historique.html.twig', [
            'covoiturages' => $covoiturages,
        ]);
    }

    #[Route('/profil/modifier', name: 'app_modifier_profil')]
    #[IsGranted('ROLE_USER')]
    public function modifierProfil(Request $request, EntityManagerInterface $em): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Profil mis à jour avec succès.');

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/modifier.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
