<?php

namespace App\Controller\admin;

use App\Entity\Meme;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class MemeCrudController extends AbstractCrudController
{
    public function __construct(
        private Security $security
    ){

    }

    public function configureActions(Actions $actions): Actions
    {
        $duplicate = Action::new('duplicate')
            ->linkToCrudAction('duplicateMeme');

        return $actions
            ->add(Crud::PAGE_EDIT,$duplicate);
    }

    public static function getEntityFqcn(): string
    {
        return Meme::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            TextEditorField::new('description'),
            BooleanField::new('visible'),
            ImageField::new('imageName')->setBasePath('images/memes')
                ->setUploadDir('public/images/memes'),
            AssociationField::new('categorie_id'),
            AssociationField::new('user_id')->hideOnForm(),
            DateTimeField::new('created_at')->hideOnForm(),
            DateTimeField::new('updated_at')->hideOnForm(),

            // AssociationField::new('categorie_id')->setQueryBuilder(function (QueryBuilder $qb) {    
            //     $qb->where('entity.visible = true');
            // }),

        ];
    }
    
    // public function persistEntity(EntityManagerInterface $em, $entityInstance): void 
    // {
    //     if(!$entityInstance instanceof Meme)return;

    //     $entityInstance->setCreatedAt(new \DateTimeImmutable())
    //         ->setUserId($this->security->getUser());

    //     parent::persistEntity($em, $entityInstance);
    // }

    // public function updateEntity(EntityManagerInterface $em, $entityInstance): void
    // {
    //     if(!$entityInstance instanceof Meme)return;

    //     $entityInstance->setUpdatedAt(new \DateTimeImmutable());

    //     parent::persistEntity($em, $entityInstance);
    // }

    public function duplicateMeme(
        EntityManagerInterface $em,
        AdminContext $context,
        AdminUrlGenerator $generator
    ): Response {
    
        /** @var Meme $meme */
        $meme = $context->getEntity()->getInstance();

        $duplicateMeme = clone $meme;

        parent::persistEntity($em, $duplicateMeme);

        $url = $generator->setController(self::class)
            ->setAction(Action::DETAIL)
            ->setEntityId($duplicateMeme->getId())
            ->generateUrl();
        
            return $this->redirect($url);
    }
    
}
