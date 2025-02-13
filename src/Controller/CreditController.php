<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CreditController extends AbstractController
{
    #[Route('/credit', name: 'app_credit')]
    public function index(): Response
    {
        return $this->render('credit/index.html.twig', [
            'controller_name' => 'CreditController',
        ]);
    }

    #[Route('/recharge', name: 'app_credit_recharge')]
    #[IsGranted('ROLE_USER')] // Seuls les utilisateurs connectés peuvent recharger leurs crédits
    public function rechargeCredit(): Response
    {
        return $this->render('credit/recharge.html.twig');
    }
}
