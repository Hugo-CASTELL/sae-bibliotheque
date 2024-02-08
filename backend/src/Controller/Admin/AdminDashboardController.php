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
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Back-Office');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Dashboard', 'fa fa-home', 'biblio');
        yield MenuItem::linkToUrl('Retour au site', 'fa fa-home', 'http://localhost:4200/');
        yield MenuItem::section('Emprunts');
        yield MenuItem::linkToRoute('Enregistrer un emprunt', 'fas fa-list', 'add_emprunt');
        yield MenuItem::linkToRoute('Retourner un emprunt', 'fas fa-list', 'return_emprunt');
        yield MenuItem::linkToRoute('Enregistrer un emprunt depuis une Réservation', 'fas fa-list', 'add_emprunt_resa');
        yield MenuItem::section('Gestion');
        yield MenuItem::linkToRoute('Adhérents avec emprunts','fa fa-user','admin/emprunteurs');
        yield MenuItem::section('Administration');
        yield MenuItem::linkToDashboard('Stats', 'fa fa-home');
        yield MenuItem::linkToCrud('Adhérents', 'fa fa-user', Adherent::class);
        yield MenuItem::linkToCrud('Auteurs', 'fa fa-user', Auteur::class);
        yield MenuItem::linkToCrud('Catégories', 'fa fa-user', Categorie::class);
        yield MenuItem::linkToCrud('Livres', 'fa fa-book', Livre::class);
        yield MenuItem::linkToCrud('Emprunts', 'fa fa-list', Emprunt::class);
        yield MenuItem::linkToCrud('Réservations', 'fa fa-list', Reservations::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', Utilisateur::class);
    }
}
