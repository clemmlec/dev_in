<?php

namespace App\Controller\admin;

use App\Entity\ArticleLiked;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ArticleLikedCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ArticleLiked::class;
    }

}
