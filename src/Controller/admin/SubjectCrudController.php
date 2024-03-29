<?php

namespace App\Controller\admin;

use App\Entity\Subject;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SubjectCrudController extends AbstractCrudController
{
    public function __construct(
        // private Security $security
    ) {
    }

    public function configureActions(Actions $actions): Actions
    {
        $duplicate = Action::new('duplicate')
            ->linkToCrudAction('duplicateSubject')
            ;

        return $actions
            ->add(Crud::PAGE_EDIT, $duplicate)
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public static function getEntityFqcn(): string
    {
        return Subject::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            AssociationField::new('forum'),
            TextEditorField::new('description'),
            ArrayField::new('subjectReports')->hideOnForm()->setSortable(false),
            BooleanField::new('active'),
            AssociationField::new('user')->hideOnForm(),
            DateTimeField::new('created_at')->hideOnForm(),
            DateTimeField::new('updated_at')->hideOnForm(),

        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['created_at' => 'DESC'])
        ;
    }

    public function duplicateSubject(
        EntityManagerInterface $em,
        AdminContext $context,
        AdminUrlGenerator $generator
    ): Response {
        /** @var Subject $subject */
        $subject = $context->getEntity()->getInstance();

        $duplicateSubject = clone $subject;

        parent::persistEntity($em, $duplicateSubject);

        $url = $generator->setController(self::class)
            ->setAction(Action::DETAIL)
            ->setEntityId($duplicateSubject->getId())
            ->generateUrl();

        return $this->redirect($url);
    }
}
