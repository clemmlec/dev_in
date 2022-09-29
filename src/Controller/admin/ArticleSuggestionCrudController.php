<?php

namespace App\Controller\admin;

use App\Entity\ArticleSuggestion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ArticleSuggestionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ArticleSuggestion::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('message'),
            AssociationField::new('user'),
            AssociationField::new('article'),
        ];
    }
}
