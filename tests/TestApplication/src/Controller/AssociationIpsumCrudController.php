<?php

namespace EasyCorp\Bundle\EasyAdminBundle\Tests\TestApplication\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Tests\TestApplication\Entity\AssociationIpsum;

class AssociationIpsumCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AssociationIpsum::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            AssociationField::new('associationLorem'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setPaginatorPageSize(100);
    }
}
