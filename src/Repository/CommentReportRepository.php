<?php

namespace App\Repository;

use App\Entity\CommentReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommentReport>
 *
 * @method CommentReport|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentReport|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentReport[]    findAll()
 * @method CommentReport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentReport::class);
    }

    public function add(CommentReport $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CommentReport $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * 
    */
   public function countComReport(): int
   {
        // $date =date("Y-(m-1)-d H:i:s")('c', mktime(1, 2, 3, 4, 5, 2006));
        // $date =date("Y-m-d H:i:s",mktime(0, 0, 0, date("m")-1, date("d"),   date("Y")));
        // $date =date_format($date, 'Y-m-d H:i:s');
       
        // dd($date);

       return $this->createQueryBuilder('c')
            ->select('count(c.id)')
            // ->where('c.created_at', ':date')
            // ->setParameter(':date',  $date)
        //  ->groupBy('c.comment')
            ->getQuery()
            ->getSingleScalarResult()
       ;
   }

//    public function findOneBySomeField($value): ?CommentReport
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
