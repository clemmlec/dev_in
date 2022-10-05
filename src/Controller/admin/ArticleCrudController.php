<?php

namespace App\Controller\admin;

use App\Entity\Article;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    // public function configureCrud(Crud $crud): Crud
    // {
    //     return $crud
    //         ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
    //     ;
    // }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            TextEditorField::new('content')->setFormType(CKEditorType::class),
            AssociationField::new('tags')->setCrudController(TagsCrudController::class),
            AssociationField::new('user')->hideOnForm(),
            ArrayField::new('articleSuggestions')->hideOnForm()->setSortable(false),
            DateTimeField::new('created_at')->hideOnForm()->setSortable(true),
            DateTimeField::new('updated_at')->hideOnForm()->setSortable(true),

            // AssociationField::new('forum')->setQueryBuilder(function (QueryBuilder $qb) {
            //     $qb->where('entity.active = true');
            // }),
        ];
    }
    
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['createdAt' => 'DESC'])
        ;
    }
}
