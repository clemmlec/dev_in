<?php

namespace App\Repository;

use App\Entity\SubjectReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SubjectReport>
 *
 * @method SubjectReport|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubjectReport|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubjectReport[]    findAll()
 * @method SubjectReport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubjectReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubjectReport::class);
    }

    public function add(SubjectReport $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SubjectReport $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * 
    */
    public function countSubjectReport(): int
    {

        return $this->createQueryBuilder('c')
             ->select('count(c.id)')
             ->getQuery()
             ->getSingleScalarResult()
        ;
    }

//    public function findOneBySomeField($value): ?SubjectReport
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
