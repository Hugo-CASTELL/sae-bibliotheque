<?php

namespace App\Controller\Admin;

use App\Entity\Livre;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class LivreCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Livre::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('titre')
                ->setLabel('Titre')
                ->setMaxLength(255),
            TextField::new('langue')
                ->setLabel('Langue')
                ->setMaxLength(255),
            TextField::new('photoCouverture')
                ->setLabel('Photo de couverture')
                ->setMaxLength(255),
            DateField::new('dateSortie')
                ->setLabel('Date de sortie'),
            AssociationField::new('categories')
                ->setLabel('Catégories'),
            AssociationField::new('auteurs')
                ->setLabel('Auteurs'),
        ];
    }
}
