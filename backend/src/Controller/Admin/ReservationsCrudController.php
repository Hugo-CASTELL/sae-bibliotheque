<?php

namespace App\Controller\Admin;

use App\Entity\Reservations;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class ReservationsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reservations::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $currentDate = new DateTimeImmutable();

        return [
            DateTimeField::new('dateResa')
                ->setLabel('Date de réservation')
                ->setRequired(true)
                ->setFormat('dd/MM/yyyy')
                ->setValue($currentDate),
            AssociationField::new('adherent')
                ->setLabel('Adhérent')
                ->setRequired(true),
            AssociationField::new('livre')
                ->setLabel('Livre')
                ->setRequired(true),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entity): void
    {
        // Récupération du livre et de l'adherent
        $livre = $entity->getLivre();
        $adherent = $entity->getAdherent();

        // Vérification de la disponibilité du livre
        if ($livre->getDisponible() === false) {
            $this->addFlash('danger', 'Le livre n\'est pas disponible');
            return;
        }

        // Si l'adherent a déjà trois réservations
        // if ($adherent->getReservations()->count() >= 3) {
        //     $this->addFlash('danger', 'L\'adherent a déjà trois réservations');
        //     return;
        // }

        // Traitement du livre
        $livre->setDisponible(false);

        // Persist et flush
        $entityManager->persist($livre);
        $entityManager->persist($entity);
        $entityManager->flush();
    }

}
