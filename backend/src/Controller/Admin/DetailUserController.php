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

class DetailUserController extends AbstractDashboardController
{

    private $adminContextProvider;
    private $adherentRepository;

    public function __construct(AdminContextProvider $adminContextProvider, AdherentRepository $adherentRepository)
    {
        $this->adminContextProvider = $adminContextProvider;
        $this->adherentRepository = $adherentRepository;
    }

    public function indexAction()
    {
        

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
        $adherentRepository = $this->adherentRepository;
        $dashboardController = new BiblioDashboardController($adherentRepository);
        return $dashboardController->configureMenuItems();
    }
}