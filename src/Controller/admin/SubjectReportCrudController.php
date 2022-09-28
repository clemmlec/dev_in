<?php

namespace App\Controller\admin;

use App\Entity\SubjectReport;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SubjectReportCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SubjectReport::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('message'),
            AssociationField::new('user'),
            AssociationField::new('subject'),
        ];
    }
    
}