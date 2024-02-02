<?php

namespace App\Controller\Admin;

use App\Entity\Reservations;
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
        return [
            DateTimeField::new('dateResa')
                ->setLabel('Date de réservation'),
            AssociationField::new('adherent')
                ->setLabel('Adhérent'),
            AssociationField::new('livre')
                ->setLabel('Livre'),
        ];
    }
}
