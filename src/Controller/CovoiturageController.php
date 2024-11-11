<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CovoiturageController extends AbstractController
{
    #[Route('/covoiturage', name: 'app_covoiturage')]
    public function index(): Response
    {
        return $this->render('covoiturage/index.html.twig', [
            'covoiturages' => 'CovoiturageController',
        ]);
    }

    #[Route('/covoiturage/ajouter', name: 'app_covoiturage_ajouter')]
    public function ajouter(): Response
    {
        return $this->render('covoiturage/ajouter.html.twig', [
            'controller_name' => 'CovoiturageController',
        ]);
    }

}
