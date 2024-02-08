<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BiblioDashboardController extends AbstractDashboardController
{
    #[Route('/biblio', name: 'bilbio')]
    public function index(): Response
    {
        return $this->render('admin/biblioDashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Back-Office');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToRoute('Enregistrer un emprunt', 'fas fa-list', 'add_emprunt');
        yield MenuItem::linkToRoute('Retourner un emprunt', 'fas fa-list', 'return_emprunt');
        yield MenuItem::linkToRoute('Enregistrer un emprunt depuis une Réservation', 'fas fa-list', 'add_emprunt_resa');
        yield MenuItem::section('Gestion');
        yield MenuItem::linkToRoute('Adhérents avec emprunts','fa fa-user','admin/emprunteurs');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
