<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CovoiturageController extends AbstractController
{
    #[Route('/covoiturage/show', name: 'covoiturage_show')]
    public function index(): Response
    {
        return $this->render('covoiturage/index.html.twig', []);
    }

    #[Route('/covoiturage/search', name: 'covoiturage_search')]
    public function search($id): Response
    {
        return $this->render('covoiturage/search.html.twig', []);
    }

    #[Route('/covoiturage/result', name: 'covoiturage_result_search')]
    public function resultSearch(): Response
    {
        return $this->render('covoiturage/result.html.twig', []);
    }
}
