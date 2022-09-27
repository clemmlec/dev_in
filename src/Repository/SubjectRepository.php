<?php

namespace App\Repository;

use App\Entity\Subject;
use App\Filter\SearchData;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Subject>
 *
 * @method Subject|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subject|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subject[]    findAll()
 * @method Subject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubjectRepository extends ServiceEntityRepository
{
    public function __construct(
        private ManagerRegistry $registry,
        private PaginatorInterface $paginator 
    )
    {
        parent::__construct($registry, Subject::class);
    }

    public function add(Subject $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Subject $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findRandSubject(): ?array
    {
        $query = $this->createQueryBuilder('a')
            ->andWhere('a.active = :active')
            ->setParameter('active', true)
            // ->orderBy('RAND()')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();

        return $query;
    }

    public function findActiveSubject(SearchData $search): PaginationInterface
    {
        $query = $this->createQueryBuilder('a')
            ->select('a', 'u', 'c', 'n', 'm', 'k')
            ->andWhere('a.active = :active')
            ->setParameter('active', true)
            ->leftjoin('a.user', 'u')
            ->leftjoin('a.forum', 'c')
            ->leftjoin('a.noteSubjects', 'n')
            ->leftjoin('a.comments', 'm')
            ->leftjoin('m.commentLikes', 'k')
            ->orderBy('a.created_at', 'DESC');

            return $this->paginator->paginate(
                $query->getQuery(),
                $search->getPage(),
                5
            );
        ;
        // dd($queryBuilder);
    }


    
//    /**
//     * @return Subject[] Returns an array of Subject objects
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

//    public function findOneBySomeField($value): ?Subject
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
