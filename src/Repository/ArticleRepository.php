<?php

namespace App\Repository;

use App\Entity\Article;
use App\Filter\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(
        private ManagerRegistry $registry,
        private PaginatorInterface $paginator
        ) {
        parent::__construct($registry, Article::class);
    }

    public function add(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findArticle(SearchData $search): PaginationInterface
    {
        $query = $this->createQueryBuilder('a')
            ->select('a', 't')
            ->leftjoin('a.tags', 't')
            ->orderBy('a.createdAt', 'DESC')
        ;

        if (!empty($search->getQuery())) {
            $query->andWhere('a.content LIKE :name')
                ->setParameter('name', "%{$search->getQuery()}%")
            ;
        }

        return $this->paginator->paginate(
            $query->getQuery(),
            $search->getPage(),
            5
        );

        // dd($queryBuilder);
    }

    
   /**
    * @return Article[] Returns an array of Article with same tags
    */
    public function findLastArticles(): array
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
        ;
    }


   /**
    * @return Article[] Returns an array of Article with same tags
    */
   public function findArticleWithSameTags($tags): array
   {
       return $this->createQueryBuilder('a')
           ->leftJoin('a.tags', 't')
           ->andWhere('t.id IN (:tags)')
           ->setParameter('tags', $tags)
           ->orderBy('a.createdAt', 'ASC')
           ->setMaxResults(5)
           ->getQuery()
           ->getResult()
       ;
   }

//    public function findOneBySomeField($value): ?Article
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
