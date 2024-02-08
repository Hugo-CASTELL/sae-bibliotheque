<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Emprunt;
use App\Entity\Adherent;
use App\Entity\Livre;
use App\Repository\EmpruntRepository;
use App\Repository\LivreRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Request;

class ReturnEmpruntController extends AbstractDashboardController
{
    #[Route('/biblio/returnEmprunt', name: 'return_emprunt')]
    public function returnEmprunt(Request $request, EmpruntRepository $empruntRepository): Response
    {
        $form = $this->createFormBuilder()
            ->add('emprunt', EntityType::class, [
                'class' => Emprunt::class,
                'query_builder' => function (EmpruntRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('e')
                        ->where('e.dateRetour IS NULL');
                },
                'choice_label' => function (Emprunt $emprunt) {
                    return $emprunt->getAdherent()->getNom() . ' ' . $emprunt->getAdherent()->getPrenom() . ' - ' . $emprunt->getLivre()->getTitre();
                }
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emprunt = $form->getData();

            $empruntRepository->returnEmprunt($emprunt['emprunt']);

            return $this->redirectToRoute('bilbio');
        }


        return $this->render('admin/returnEmprunt.html.twig', [
            'form' => $form,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Backend');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
