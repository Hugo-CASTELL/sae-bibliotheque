<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AdherentRepository;

class BiblioDashboardController extends AbstractDashboardController
{

    public function __construct(
        private AdherentRepository $adherentRepository,
    ) {}

    #[Route('/biblio', name: 'biblio')]
    public function index(): Response
    {
        $adwithempruntsatemps = $this->adherentRepository->findHasEmpruntsATemps();
        $adwithempruntsenretard = $this->adherentRepository->findHasEmpruntsEnRetard();
        return $this->render('admin/biblioDashboard.html.twig',[
            'adwithempruntsatemps' => $adwithempruntsatemps,
            'adwithempruntsenretard' => $adwithempruntsenretard]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Back-Office');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToUrl('Dashboard', 'fa fa-home','/biblio');
        yield MenuItem::linkToRoute('Enregistrer un emprunt', 'fas fa-list', 'add_emprunt');
        yield MenuItem::section('Gestion');
        yield MenuItem::linkToRoute('Adhérents avec emprunts','fa fa-list','admin/emprunteurs');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
