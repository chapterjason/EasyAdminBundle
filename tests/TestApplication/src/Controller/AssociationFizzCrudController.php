<?php

namespace EasyCorp\Bundle\EasyAdminBundle\Tests\TestApplication\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Tests\TestApplication\Entity\AssociationFizz;

class AssociationFizzCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AssociationFizz::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            AssociationField::new('associationBaz'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setPaginatorPageSize(100);
    }
}
