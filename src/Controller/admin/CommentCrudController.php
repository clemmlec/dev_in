<?php

namespace App\Controller\admin;

use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('message'),
            AssociationField::new('user')->hideOnForm(),
            AssociationField::new('subject')->hideOnForm(),
            BooleanField::new('active'),
            AssociationField::new('commentReports')->setSortable(true)->hideOnForm(),
        ];
    }

    // public function configureFilters(Filters $filters): Filters
    // {
    //     return $filters
    //         ->add('commentReports');
    // }
}
