<?php

namespace App\Repository;

use App\Filter\SearchData;
use App\Entity\ArticleLiked;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<ArticleLiked>
 *
 * @method ArticleLiked|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleLiked|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleLiked[]    findAll()
 * @method ArticleLiked[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleLikedRepository extends ServiceEntityRepository
{

    public function __construct(
        protected ManagerRegistry $registry,
        private PaginatorInterface $paginator
        )
    {
        parent::__construct($registry, ArticleLiked::class);
    }

    public function add(ArticleLiked $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ArticleLiked $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    
   public function getArticleFavoris($id,SearchData $search): PaginationInterface
   {
       $query = $this->createQueryBuilder('a')
            ->select('a', 'u', 'c')
            ->andWhere('a.user = :val')
            ->setParameter('val', $id)
            ->leftjoin('a.article', 'u')
            ->leftjoin('u.tags', 'c')
            ->orderBy('a.id', 'DESC')
        ;
        if (!empty($search->getQuery())) {
            $query->andWhere('u.content LIKE :name')
                ->setParameter('name', "%{$search->getQuery()}%")
            ;
        }

        return $this->paginator->paginate(
            $query->getQuery(),
            $search->getPage(),
            10
        );
   }

}
