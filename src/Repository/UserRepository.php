<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function add(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findUsers(): array
    {
        return $this->createQueryBuilder('u')
            ->select('u', 'a', 'n', 'c')
            // ->select('u', 'f', 'r','a','n','c')
            // ->leftjoin('u.follows', 'f')
            // ->leftjoin('u.followers', 'r')
            ->leftjoin('u.memes', 'a')
            ->leftjoin('a.noteMemes', 'n')
            ->leftjoin('u.comments', 'c')
            ->orderBy('a.created_at', 'DESC')
            ->getQuery()
            ->getResult()
        ;
        // dd($queryBuilder);
    }

    public function findOneById($id): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.id = :val')
            ->setParameter('val', $id)
            ->select('u', 'a', 'z', 'n', 'c')
            ->leftjoin('u.memes', 'a')
            ->leftjoin('u.memeFavoris', 'z')
            ->leftjoin('a.noteMemes', 'n')
            ->leftjoin('u.comments', 'c')
            ->getQuery()
            ->getResult()
        ;
        // dd($queryBuilder);
    }
//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->setFirstResult(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
