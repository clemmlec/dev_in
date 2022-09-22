<?php

namespace App\Controller\admin;

use App\Entity\Forum;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ForumCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Forum::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            TextField::new('style'),
            ImageField::new('imageName')->setBasePath('images/forum')
            ->setUploadDir('public/images/forum'),
        ];
    }
    
    // public function deleteEntity(EntityManagerInterface $em, $entityInstance): void
    // {
    //      if (!$entityInstance instanceof Forum) return;

    //      foreach ($entityInstance->getSubjects() as $subject){
    //         $em->remove($subject);
    //      }

    //      parent::deleteEntity($em,$entityInstance);
    // }
}
