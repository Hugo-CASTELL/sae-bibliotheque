<?php

namespace App\Controller\Admin;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AdherentRepository;
use App\Repository\EmpruntRepository;
class DetailUserController extends BiblioDashboardController
{

    private $adminContextProvider;

    public function __construct(AdminContextProvider $adminContextProvider, AdherentRepository $adherentRepository, EmpruntRepository $empruntRepository)
    {
        $this->adminContextProvider = $adminContextProvider;
        // Construct the parent class
        parent::__construct($adherentRepository, $empruntRepository);
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

}