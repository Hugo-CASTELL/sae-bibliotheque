<?php

namespace App\Controller\Admin;

use App\Entity\Emprunt;
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
                ->setLabel('Date d\'emprunt'),
            DateTimeField::new('dateRetour')
                ->setLabel('Date de retour'),
            AssociationField::new('adherent')
                ->setLabel('Adhérent'),
            AssociationField::new('livre')
                ->setLabel('Livre'),
        ];
    }
}
