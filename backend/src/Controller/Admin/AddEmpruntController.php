<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

use App\Form\AddEmpruntResa;
use App\Entity\Reservations;

class AddEmpruntController extends AbstractController
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
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emprunt = $form->getData();

            $empruntRepository->save($emprunt, true);

            return $this->redirectToRoute('biblio');
        }

        return $this->render('admin/addEmpruntbiblioDashboard.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/biblio/addEmpruntResa', name: 'add_emprunt_resa')]
    public function addEmpruntResa(Request $request, EmpruntRepository $empruntRepository): Response
    {
        $form = $this->createFormBuilder()
            ->add('reservation', EntityType::class, [
                'class' => Reservations::class,
                'choice_label' => function (Reservations $reservations) {
                    return $reservations->getAdherent()->getNom() . ' ' . $reservations->getAdherent()->getPrenom() . ' - ' . $reservations->getLivre()->getTitre();
                }
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $empruntRepository->addEmpruntResa($data['reservation']);

            return $this->redirectToRoute('biblio');
        }


        return $this->render('admin/addEmpruntbiblioResaDashboard.html.twig', [
            'form' => $form,
        ]);
    }
}
