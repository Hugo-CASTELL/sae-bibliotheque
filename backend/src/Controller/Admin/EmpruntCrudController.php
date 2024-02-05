<?php

namespace App\Controller\Admin;

use App\Entity\Emprunt;
use DateInterval;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class EmpruntCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Emprunt::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $currentDate = new DateTimeImmutable();
        $twoWeeks = new DateInterval('P15D');
        $twoWeeksLater = $currentDate->add($twoWeeks);

        return [
            DateTimeField::new('dateEmprunt')
                ->setLabel('Date d\'emprunt')
                ->setRequired(true)
                ->setFormat('dd/MM/yyyy')
                ->setValue($currentDate),
            DateTimeField::new('dateRetour')
                ->setLabel('Date de retour')
                ->setRequired(true)
                ->setFormat('dd/MM/yyyy')
                ->setValue($twoWeeksLater),
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
        // Récupération du livre
        $livre = $entity->getLivre();
        $livre->setDisponible(false);

        // Suppression de la réservation si elle existe
        $reservationsAdherent = $entity->getAdherent()->getReservations();
        foreach ($reservationsAdherent as $reservation) {
            if ($reservation->getLivre() === $livre) {
                $entityManager->remove($reservation);
                $entityManager->persist($reservation);
                break;
            }
        }

        // Persist et flush
        $entityManager->persist($livre);
        $entityManager->persist($entity);
        $entityManager->flush();
    }

}

