<?php

namespace App\Controller\Admin;

use App\Entity\Auteur;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AuteurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Auteur::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nom')
                ->setLabel('Nom de l\'auteur')
                ->setMaxLength(255),
            TextField::new('prenom')
                ->setLabel('Prenom de l\'auteur')
                ->setMaxLength(255),
            TextField::new('nationalite')
                ->setLabel('Nationalité')
                ->setMaxLength(255),
            TextField::new('photo')
                ->setLabel('Photo de l\'auteur')
                ->setMaxLength(255),
            TextEditorField::new('description')
                ->setLabel('Description'),
            DateField::new('dateNaissance')
                ->setLabel('Date de naissance'),
            DateField::new('dateDeces')
                ->setLabel('Date de décès'),
            AssociationField::new('livres')
                ->setLabel('Livres'),
        ];
    }
}
