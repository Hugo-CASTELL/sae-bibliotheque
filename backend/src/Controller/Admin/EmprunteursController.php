<?php

namespace App\Controller\Admin;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AdherentRepository;
use App\Repository\EmpruntRepository;

class EmprunteursController extends AbstractDashboardController
{

    private $adminContextProvider;
    private $adherentRepository;
    private $empruntRepository;

    public function __construct(AdminContextProvider $adminContextProvider, AdherentRepository $adherentRepository, EmpruntRepository $empruntRepository)
    {
        $this->adminContextProvider = $adminContextProvider;
        $this->adherentRepository = $adherentRepository;
        $this->empruntRepository = $empruntRepository;
    }

    public function indexAction()
    {
        

    }

    #[Route('/admin/emprunteurs', name: 'admin/emprunteurs')]
    public function index(): Response
    {
                // Get the result from the repository
        //find where getEmprunts not null
        $result = $this->adherentRepository->findHasEmprunts();
        //for each result pass a link parameter set to the detail_emprunteur route but
        $empruntsenc = $this->empruntRepository->findEmpruntsEnCours();
        $empruntsenret = $this->empruntRepository->findEmpruntsEnRetard();
        // Set the result to the template parameters
        return $this->render('admin/emprunteurs.html.twig', [
            'result' => $result,
            'empruntsenc' => $empruntsenc,
            'empruntsenret' => $empruntsenret
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Backend');
    }
    
}