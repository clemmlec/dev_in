<?php

namespace App\Controller\admin;

use App\Entity\Article;
use App\Entity\Forum;
use App\Entity\Subject;
use App\Entity\Tags;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator
    ) {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator
            ->setController(ForumCrudController::class)
            ->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Dev In')
            ->setFaviconPath('images/logos.png');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Forum');
        yield MenuItem::subMenu('Actions', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Add Forum', 'fas fa-plus', Forum::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show Forum', 'fas fa-eye', Forum::class),
        ]);

        yield MenuItem::section('Subject');
        yield MenuItem::subMenu('Actions', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Add Subject', 'fas fa-plus', Subject::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show Subject', 'fas fa-eye', Subject::class),
        ]);

        yield MenuItem::section('Tags');
        yield MenuItem::subMenu('Actions', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Add Tags', 'fas fa-plus', Tags::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show Tags', 'fas fa-eye', Tags::class),
        ]);

        yield MenuItem::section('Article');
        yield MenuItem::subMenu('Actions', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Add Article', 'fas fa-plus', Article::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show Article', 'fas fa-eye', Article::class),
        ]);

        yield MenuItem::section('User');
        yield MenuItem::subMenu('Actions', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Show User', 'fas fa-eye', User::class),
        ]);

        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
