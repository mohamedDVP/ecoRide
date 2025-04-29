<?php

namespace App\Controller;

use App\Repository\CovoiturageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    #[Route('/switch-role', name: 'app_switch_role', methods: ['POST'])]
    #[IsGranted('ROLE_USER')] // Vérifie que l'utilisateur est connecté
    public function switchRole(Request $request, EntityManagerInterface $em, Security $security): JsonResponse
    {
        $user = $security->getUser();

        if (!$user) {
            return new JsonResponse(['success' => false, 'message' => 'Utilisateur non authentifié.'], 403);
        }

        $data = json_decode($request->getContent(), true);
        $newRole = $data['role'] ?? '';

        if ($newRole === 'conducteur') {
            $user->setRoles(['ROLE_USER', 'ROLE_CONDUCTEUR']);
        } elseif ($newRole === 'passager') {
            $user->setRoles(['ROLE_USER']);
        } else {
            return new JsonResponse(['success' => false, 'message' => 'Rôle invalide.'], 400);
        }

        $em->persist($user);
        $em->flush();

        return new JsonResponse(['success' => true, 'message' => 'Rôle mis à jour.']);
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
}
