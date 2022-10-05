<?php

namespace App\Repository;

use App\Entity\ArticleSuggestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ArticleSuggestion>
 *
 * @method ArticleSuggestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleSuggestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleSuggestion[]    findAll()
 * @method ArticleSuggestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleSuggestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticleSuggestion::class);
    }

    public function add(ArticleSuggestion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ArticleSuggestion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function countArticleReport(): int
    {

        return $this->createQueryBuilder('c')
             ->select('count(c.id)')
             ->getQuery()
             ->getSingleScalarResult()
        ;
    }

//    /**
//     * @return ArticleSuggestion[] Returns an array of ArticleSuggestion objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ArticleSuggestion
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
