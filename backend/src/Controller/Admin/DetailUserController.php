<?php

namespace App\Controller\Admin;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AdherentRepository;
use App\Controller\Admin\BiblioDashboardController;
use App\Entity\Adherent;
use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Entity\Livre;
use App\Entity\Emprunt;
use App\Entity\Reservations;
use App\Entity\Utilisateur;
class DetailUserController extends AbstractDashboardController
{

    private $adminContextProvider;
    private $adherentRepository;

    public function __construct(AdminContextProvider $adminContextProvider, AdherentRepository $adherentRepository)
    {
        $this->adminContextProvider = $adminContextProvider;
        $this->adherentRepository = $adherentRepository;
    }

    #[Route('/biblio/emprunteur/{id}', name: 'detail_emprunteur')]
    public function indexEmprunteur(int $id): Response
    {
                // Get the result from the repository
        //find where getEmprunts not null order by dateEmprunt
        $result = $this->adherentRepository->findOneBy(['id' => $id]);

        // Set the result to the template parameters
        return $this->render('admin/detailAdherent.html.twig', [
            'adherent' => $result,
        ]);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToUrl('Retour au site', 'fa fa-home', 'http://localhost:4200/');
        yield MenuItem::section('Emprunts');
        yield MenuItem::linkToRoute('Enregistrer un emprunt', 'fas fa-list', 'add_emprunt');
        yield MenuItem::linkToRoute('Enregistrer un emprunt depuis une Réservation', 'fas fa-list', 'add_emprunt_resa');
        yield MenuItem::linkToRoute('Retourner un emprunt', 'fas fa-list', 'return_emprunt');
        yield MenuItem::section('Gestion');
        yield MenuItem::linkToRoute('Adhérents avec emprunts','fa fa-user','admin/emprunteurs');

        if ($this->isGranted('ROLE_ADMIN')) {
            yield MenuItem::section('Administration');
            yield MenuItem::linkToRoute('Stats', 'fa fa-home', 'admin');
            yield MenuItem::linkToCrud('Adhérents', 'fa fa-user', Adherent::class);
            yield MenuItem::linkToCrud('Auteurs', 'fa fa-user', Auteur::class);
            yield MenuItem::linkToCrud('Catégories', 'fa fa-user', Categorie::class);
            yield MenuItem::linkToCrud('Livres', 'fa fa-book', Livre::class);
            yield MenuItem::linkToCrud('Emprunts', 'fa fa-list', Emprunt::class);
            yield MenuItem::linkToCrud('Réservations', 'fa fa-list', Reservations::class);
            yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', Utilisateur::class);
        }

        yield MenuItem::linkToLogout('Logout', 'fa fa-exit');
    }
}