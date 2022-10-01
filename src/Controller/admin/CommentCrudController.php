<?php

namespace App\Controller\admin;

use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

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
            ArrayField::new('commentReports')->setSortable(false)->hideOnForm(),
            BooleanField::new('active'),
        ];
    }

    // public function configureFilters(Filters $filters): Filters
    // {
    //     return $filters
    //         ->add('commentReports');
    // }
}
