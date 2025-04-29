<?php
namespace App\Controller;

use App\Entity\Voiture;
use App\Form\VoitureType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VoitureController extends AbstractController
{
    #[Route('/voiture', name: 'voiture_list')]
    public function index(): Response
    {
        return $this->render('voiture/index.html.twig', [
            'voitures' => 'VoitureController',
        ]);
    }
    
    #[Route('/voiture/new', name: 'voiture_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $voiture = new Voiture();
        $form = $this->createForm(VoitureType::class, $voiture);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($voiture);
            $entityManager->flush();

            return $this->redirectToRoute('voiture_list'); // Modifier selon votre route
        }

        return $this->render('voiture/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
