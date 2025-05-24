<?php

namespace App\Controller\Admin;

use App\Entity\Avis;
use App\Entity\Configuration;
use App\Entity\Covoiturage;
use App\Entity\Marque;
use App\Entity\Parametre;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Voiture;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator
        )
        {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator
            ->setController(UserCrudController::class)
            ->generateUrl();

        return $this->redirect($url);
        
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('EcoRide');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section("Gestion des utilisateurs");
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Roles','fas fa-user', Role::class);
        yield MenuItem::section("Gestion des covoiturages");
        yield MenuItem::linkToCrud('Covoiturages','fas fa-flag-checkered', Covoiturage::class);
        yield MenuItem::linkToCrud('Voiture','fas fa-car', Voiture::class);
        yield MenuItem::linkToCrud('Marque','fas fa-trademark', Marque::class);
        yield MenuItem::section("Gestion des avis");
        yield MenuItem::linkToCrud('Avis','fas fa-star', Avis::class);
        yield MenuItem::section('Parametres');
        yield MenuItem::linkToCrud('Configuration','fas fa-sliders', Configuration::class);
        yield MenuItem::linkToCrud('Paramètres','fas fa-gear', Parametre::class);


    }
}
