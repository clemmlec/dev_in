<?php

namespace App\Controller\admin;

use App\Entity\Tags;
use App\Entity\User;
use App\Entity\Forum;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Subject;
use App\Entity\CommentReport;
use App\Entity\SubjectReport;
use App\Entity\ArticleSuggestion;
use App\Repository\TagsRepository;
use Symfony\UX\Chartjs\Model\Chart;
use App\Repository\ArticleRepository;
use App\Repository\CommentReportRepository;
use App\Repository\SubjectReportRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ArticleSuggestionRepository;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use App\Controller\admin\CommentReportCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    
     

    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator,
        private CommentReportRepository $comRepo,
        private SubjectReportRepository $subRepo,
        private ArticleSuggestionRepository $artRepo,
        private TagsRepository $tagsRepo,
        private ChartBuilderInterface $chartBuilder
    ) {
    }




    // #[Route('/admin', name: 'admin')]
    // public function index(): Response
    // {
    //     $url = $this->adminUrlGenerator
    //         ->setController(CommentReportCrudController::class)
    //         ->generateUrl();

    //     return $this->redirect($url);
    // }
    #[Route('/admin', name: 'admin')]
    public function dashBoard(): Response
    {

        $charts = $this->charts();
        return $this->render('admin/dashboard.html.twig', [
            'chart' => $charts[0],
            'chart2' => $charts[1],
            'chart3' => $charts[2],
        ]);
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
            MenuItem::linkToCrud('Show Report', 'fas fa-eye', SubjectReport::class),
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
            MenuItem::linkToCrud('Show suggest', 'fas fa-eye', ArticleSuggestion::class),
        ]);

        yield MenuItem::section('User');
        yield MenuItem::linkToCrud('Show User', 'fas fa-eye', User::class);

        yield MenuItem::section('Comment');
        yield MenuItem::subMenu('Actions', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Show Comment', 'fas fa-eye', Comment::class),
            MenuItem::linkToCrud('Show Report', 'fas fa-eye', CommentReport::class),
        ]);
    }

    public function charts(): array
    {
        $comReport=$this->comRepo->countComReport();
        $subReport=$this->subRepo->countSubjectReport();
        $artReport=$this->artRepo->countArticleReport();
        // dd($subReport);
        $chart = $this->chartBuilder->createChart(Chart::TYPE_BAR);
        $chart->setData([
            'labels' => [ 'Comment Report', 'Sujet Report', 'Suggest Article',  ],
            'datasets' => [
                [
                    'label' => 'Total',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [$comReport, $subReport, $artReport, 2, 20, 30, 45],
                    'backgroundColor' => ['#FFFF55','#FF55FF','#8FF55F','#55FFFF','#453264']
                ],
            ],

        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 50,
                    'beginAtZero'=> true
                ],
            ],
            // 'indexAxis' => 'y'

           
        ]);



        $TagsArticles=$this->tagsRepo->countArticles();
        // dd($TagsArticles);
        $tableau=[];
        $tableauNom=[];
        foreach($TagsArticles as $article){
            // dd($article->getName());
            array_push($tableau, count($article->getArticle())) ;
            array_push($tableauNom, $article->getName()) ;
        }
        // dd($tableau);
        $chart2 = $this->chartBuilder->createChart(Chart::TYPE_DOUGHNUT);
        $chart2->setData([
            'labels' => $tableauNom,
            'datasets' => [
                [
                    'label' => 'Total',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $tableau,
                    'backgroundColor' => ['#FFFF55','#FF55FF','#8FF55F','#55FFFF','#453264','#2735FF','#8FF599','#122340','#410264']
                ],
            ],

        ]);

        $chart2->setOptions([
            'scales' => [
                'y' => [
                    'beginAtZero'=> true
                ],
            ],
            // 'indexAxis' => 'y'

           
        ]);


        $articleReport=$this->tagsRepo->countArticles();
        // dd($articleReport);
        $tableau=[];
        $tableauNom=[];
        foreach($articleReport as $article){
            // dd($article->getName());
            array_push($tableau, count($article->getArticle())) ;
            array_push($tableauNom, $article->getName()) ;
        }
        // dd($tableau);
        $chart3 = $this->chartBuilder->createChart(Chart::TYPE_DOUGHNUT);
        $chart3->setData([
            'labels' => $tableauNom,
            'datasets' => [
                [
                    'label' => 'Total',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $tableau,
                    'backgroundColor' => ['#FFFF55','#FF55FF','#8FF55F','#55FFFF','#453264','#2735FF','#8FF599','#122340','#410264']
                ],
            ],

        ]);

        $chart3->setOptions([
            'scales' => [
                'y' => [
                    'beginAtZero'=> true
                ],
            ],
            // 'indexAxis' => 'y'

           
        ]);

        return [$chart, $chart2, $chart3];
    }
}
