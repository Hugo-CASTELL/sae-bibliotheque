<?php

namespace App\Controller\Admin;

use App\Entity\Emprunt;
use App\Entity\Livre;
use DateInterval;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
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

        return [
            DateTimeField::new('dateEmprunt')
                ->setLabel('Date d\'emprunt')
                ->setRequired(true)
                ->setFormat('dd/MM/yyyy'),
            DateTimeField::new('dateRetour')
                ->setLabel('Date de retour')
                ->setRequired(true)
                ->setFormat('dd/MM/yyyy'),
            AssociationField::new('adherent')
                ->setLabel('Adhérent')
                ->setRequired(true),
            AssociationField::new('livre')
                ->setLabel('Livre')
                ->setRequired(true),
        ];
    }

    public function createEntity(string $entityFqcn)
    {
        $currentDate = new DateTimeImmutable();
        $twoWeeks = new DateInterval('P15D');
        $twoWeeksLater = $currentDate->add($twoWeeks);

        $emprunt = new Emprunt();
        $emprunt->setDateEmprunt($currentDate);
        $emprunt->setDateRetour($twoWeeksLater);

        return $emprunt;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entity): void
    {
        // Récupération du livre
        $livre = $entity->getLivre();
        $adherent = $entity->getAdherent();

        // Vérification de la disponibilité du livre
        if ($livre->isReservedBy($adherent) === false) {
            $this->addFlash('danger', 'Le livre n\'est pas disponible');
            return;
        }

        // Suppression de la réservation si elle existe
        $reservationsAdherent = $adherent->getReservations();
        foreach ($reservationsAdherent as $reservation) {
            if ($reservation->getLivre()->getTitre() === $livre->getTitre()) {
                $entityManager->remove($reservation);
                break;
            }
        }

        // Persist et flush
        $entityManager->persist($entity);
        $entityManager->flush();
    }

}

