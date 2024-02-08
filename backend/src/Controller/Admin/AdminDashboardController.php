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
use App\Repository\EmpruntRepository;
use App\Repository\LivreRepository;
use App\Repository\ReservationsRepository;
use App\Repository\AdherentRepository;

class AdminDashboardController extends AbstractDashboardController
{
    public function __construct(
        private EmpruntRepository $empruntRepository,
        private ReservationsRepository $reservationsRepository,
        private LivreRepository $livreRepository,
        private AdherentRepository $adherentRepository,
    ) {}

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $emprunts = $this->empruntRepository->findAll();
        $empruntsEnCours = $this->empruntRepository->findBy(['dateRetour' => null]);
        $reservations = $this->reservationsRepository->findAll();
        $livres = $this->livreRepository->findAll();
        
        $adherents = $this->adherentRepository->findAll();
        $adwithemprunts = $this->adherentRepository->findHasEmprunts();
        $adwithempruntsatemps = $this->adherentRepository->findHasEmpruntsATemps();
        $adwithempruntsenretard = $this->adherentRepository->findHasEmpruntsEnRetard();
        $livreDispo = [];

        foreach ($livres as $livre) {
            $livreEmprunts = $livre->getEmprunts();
            $livreReservations = $livre->getReservations();

            if ($livreReservations === null) {
                if (count($livreEmprunts) === 0) {
                    $livreDispo[] = $livre;
                } else {
                    $empruntFinis = 0;
                    foreach ($livreEmprunts as $emprunt) {
                        if ($emprunt->getDateRetour() != null) {
                            $empruntFinis++;
                        }
                    }
    
                    if ($empruntFinis === count($livreEmprunts)) {
                        $livreDispo[] = $livre;
                    }
                }
            }
        }

        return $this->render('admin/dashboard.html.twig', [
            'emprunts' => $emprunts,
            'empruntsEnCours' => $empruntsEnCours,
            'reservations' => $reservations,
            'livres' => $livres,
            'livreDispo' => $livreDispo,
            'adherents' => $adherents,
            'adwithemprunts' => $adwithemprunts,
            'adwithempruntsatemps' => $adwithempruntsatemps,
            'adwithempruntsenretard' => $adwithempruntsenretard,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Back-Office');
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
