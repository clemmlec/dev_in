<?php

namespace App\Controller\admin;

use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CategorieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Categorie::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            TextField::new('style'),
        ];
    }
    
    // public function deleteEntity(EntityManagerInterface $em, $entityInstance): void
    // {
    //      if (!$entityInstance instanceof Categorie) return;

    //      foreach ($entityInstance->getMemes() as $meme){
    //         $em->remove($meme);
    //      }

    //      parent::deleteEntity($em,$entityInstance);
    // }
}
