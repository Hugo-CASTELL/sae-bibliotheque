<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Utilisateur;
use App\Entity\Adherent;
use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Entity\Emprunt;
use App\Entity\Livre;
use App\Entity\Reservations;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Backend');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Items');
        yield MenuItem::linkToCrud('Adherents', 'fa fa-user', Adherent::class);
        yield MenuItem::linkToCrud('Auteur', 'fa fa-user', Auteur::class);
        yield MenuItem::linkToCrud('Categorie', 'fa fa-user', Categorie::class);
        yield MenuItem::linkToCrud('Emprunt', 'fa fa-user', Emprunt::class);
        yield MenuItem::linkToCrud('Livre', 'fa fa-user', Livre::class);
        yield MenuItem::linkToCrud('Reservations', 'fa fa-user', Reservations::class);
        yield MenuItem::section('Configuration');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', Utilisateur::class);
    }
}
