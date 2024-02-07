<?php

namespace App\Controller\Admin;

use App\Entity\Adherent;
use DateTime;
use DateTimeImmutable;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;

class AdherentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Adherent::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('prenom')
            ->setLabel('Prenom de l\'adherent')
            ->setMaxLength(255),
            TextField::new('nom')
                ->setLabel('Nom de l\'adherent')
                ->setMaxLength(255),
            TextField::new('email')
                ->setLabel('Email de l\'adherent')
                ->setMaxLength(255),
            TextField::new('adressePostale')
                ->setLabel('Adresse de l\'adherent')
                ->setMaxLength(255),
            TextField::new('numTel')
                ->setLabel('Numéro de téléphone de l\'adherent')
                ->setMaxLength(255),
            TextField::new('photo')
                ->setLabel('Photo de l\'adherent')
                ->setMaxLength(255),
            DateField::new('dateNaissance')
                ->setLabel('Date de naissance'),
            DateField::new('dateAdhesion')
                ->setLabel('Date d\'adhésion')
                ->setFormat('dd-MM-yyyy')
                ->setRequired(true),
            AssociationField::new('utilisateur')
                ->setLabel('Utilisateur de l\'adherent')
        ];
    }

    public function createEntity(string $entityFqcn)
    {
        $adherent = new Adherent();
        $adherent->setDateAdhesion(new DateTimeImmutable());
        return $adherent;
    }
}
