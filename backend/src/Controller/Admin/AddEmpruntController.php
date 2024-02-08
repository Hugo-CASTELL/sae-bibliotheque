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

class AddEmpruntController extends AbstractDashboardController
{
    #[Route('/biblio/addEmprunt', name: 'add_emprunt')]
    public function addEmprunt(Request $request, EmpruntRepository $empruntRepository): Response
    {
        $emprunt = new Emprunt();
        $emprunt->setDateEmprunt(new \DateTimeImmutable());

        $form = $this->createFormBuilder($emprunt)
            ->add('adherent', EntityType::class, [
                'class' => Adherent::class,
            ])
            ->add('livre', EntityType::class, [
                'class' => Livre::class,
                'query_builder' => function (LivreRepository $lr): QueryBuilder {
                    return $lr->livreDispo();
                },
                'choice_label' => 'titre',
            ])
            ->add('dateEmprunt', DateType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emprunt = $form->getData();

            $empruntRepository->save($emprunt, true);

            return $this->redirectToRoute('bilbio');
        }


        return $this->render('admin/addEmpruntbiblioDashboard.html.twig', [
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
