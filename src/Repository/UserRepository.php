<?php

namespace App\Repository;

use App\Entity\User;
use App\Filter\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

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
    public function __construct(
        protected ManagerRegistry $registry,
        private PaginatorInterface $paginator
        ) {
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

    public function findUsers(SearchData $search): PaginationInterface
    {
        $query = $this->createQueryBuilder('u')
            ->select('u', 'a', 'n', 'c')
            ->where('u.active = true')
            // ->select('u', 'f', 'r','a','n','c')
            // ->leftjoin('u.follows', 'f')
            // ->leftjoin('u.followers', 'r')
            ->leftjoin('u.subjects', 'a')
            ->leftjoin('a.noteSubjects', 'n')
            ->leftjoin('u.comments', 'c')

        ;
        if (!empty($search->getQuery())) {
            $query->andWhere('u.name LIKE :name')
                ->setParameter('name', "%{$search->getQuery()}%")
            ;
        }

        return $this->paginator->paginate(
            $query->getQuery(),
            $search->getPage(),
            10
        );
        // dd($queryBuilder);
    }

    public function findOneById($id): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.id = :val')
            ->setParameter('val', $id)
            ->select('u', 'a', 'z', 'n', 'c')
            ->leftjoin('u.subjects', 'a')
            ->leftjoin('u.subjectFavoris', 'z')
            ->leftjoin('a.noteSubjects', 'n')
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
