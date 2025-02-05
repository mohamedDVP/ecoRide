<?php

namespace App\Controller;

use App\Entity\Covoiturage;
use App\Form\CovoiturageType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CovoiturageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CovoiturageController extends AbstractController
{
    #[Route('/covoiturage', name: 'app_covoiturage')]
    public function index(Request $request, CovoiturageRepository $covoiturageRepository): Response
    {
        // Récupérer les paramètres de la requête (si présents)
        $covoiturages = $covoiturageRepository->findAll();

        return $this->render('covoiturage/index.html.twig', [
            'covoiturages' => $covoiturages,
        ]);
    }

    #[Route('/covoiturage/ajouter', name: 'app_covoiturage_ajouter')]
    public function ajouter(Request $request, EntityManagerInterface $entityManager): Response
    {
        $covoiturage = new Covoiturage();
        $form = $this->createForm(CovoiturageType::class, $covoiturage);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($covoiturage);
            $entityManager->flush();

            $this->addFlash('success', 'Le covoiturage a été ajouté avec succès.');

            return $this->redirectToRoute('covoiturage_liste'); // Changez selon votre route
        }

        return $this->render('covoiturage/ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/covoiturage/liste', name: 'covoiturage_liste')]
    public function liste(): Response
    {
        return $this->render('covoiturage/index.html.twig', [
            'covoiturages' => 'CovoiturageController',
        ]);
    }

    #[Route('/covoiturages/recent', name: 'covoiturages_recent')]
    public function recentCovoiturages(CovoiturageRepository $covoiturageRepository): Response
    {
        $recentCovoiturages = $covoiturageRepository->findRecentCovoiturages(new \DateTime('-30 days'));

        return $this->render('covoiturage/list.html.twig', [
            'covoiturages' => $recentCovoiturages,
        ]);
    }

    #[Route('/covoiturages/destination/{destination}', name: 'covoiturages_by_destination')]
    public function byDestination(string $destination, CovoiturageRepository $covoiturageRepository): Response
    {
        $covoiturages = $covoiturageRepository->findByDestination($destination);

        return $this->render('covoiturage/list.html.twig', [
            'covoiturages' => $covoiturages,
        ]);
    }


    #[Route('/covoiturage/{id}', name: 'covoiturage_detail', requirements: ['id' => '\d+'])]
    public function show(int $id, CovoiturageRepository $repo): Response
    {
        $covoiturage = $repo->find($id);

        if (!$covoiturage) {
            throw $this->createNotFoundException('Ce covoiturage n\'existe pas.');
        }

        return $this->render('covoiturage/detail.html.twig', [
            'covoiturage' => $covoiturage,
        ]);
    }

}
