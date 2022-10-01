<?php

namespace App\Repository;

use App\Entity\Follow;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Follow>
 *
 * @method Follow|null find($id, $lockMode = null, $lockVersion = null)
 * @method Follow|null findOneBy(array $criteria, array $orderBy = null)
 * @method Follow[]    findAll()
 * @method Follow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FollowRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        )
    {
        parent::__construct($registry, Follow::class);
    }

    public function add(Follow $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Follow $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return Follow[] Returns an array of Follow objects
    */
   public function findAllFollowed($id): array
   {
       return $this->createQueryBuilder('f')
           ->andWhere('f.user = :val')
           ->setParameter('val', $id)
           ->orderBy('f.id', 'DESC')
           ->getQuery()
           ->getResult()
       ;
   }

//    public function findAllGreaterThanPrice(int $id): array
//    {
//     $conn = $this->getEntityManager()->getConnection();

//     $sql = '
//         SELECT f.friend_id, u.* FROM follow f
//         INNER JOIN user u ON f.user_id = u.id
//         WHERE f.user_id = :id
//         ORDER BY f.id DESC
//         ';
//     $stmt = $conn->prepare($sql);
//     $resultSet = $stmt->executeQuery(['id' => $id]);

//     // returns an array of arrays (i.e. a raw data set)
//     return $resultSet->fetchAllAssociative();
//    }

    /**
     * @return Follow[] Returns an array of Follow objects
     */
    public function findAllFollowers($id): array
    {
        return $this->createQueryBuilder('f')
            ->select('f')
            ->andWhere('f.friend = :val')
            ->setParameter('val', $id)
            ->orderBy('f.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    
//    public function findOneBySomeField($value): ?Follow
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
