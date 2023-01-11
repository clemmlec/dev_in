<?php

namespace App\Repository;

use App\Entity\Subject;
use App\Entity\NoteSubject;
use App\Filter\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

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
        protected ManagerRegistry $registry,
        private PaginatorInterface $paginator
    ) {
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

    public function findLastSubject(): ?array
    {
        $query = $this->createQueryBuilder('a')
            ->andWhere('a.active = :active')
            ->setParameter('active', true)
            ->orderBy('a.created_at', 'DESC')
            // ->orderBy('RAND()')
            ->setMaxResults(8)
            ->getQuery()
            ->getResult();

        return $query;
    }

    public function findActiveSubject(SearchData $search): PaginationInterface
    {
        $query = $this->createQueryBuilder('a')
            ->select('a', 'u', 'c', 'n', 'm', 'k')
            // ->select('a', 'u', 'c', 'n', 'm', 'k','(SELECT AVG(t.note) FROM App:NoteSubject t WHERE t.subject = a) AS avg_note')
            ->andWhere('a.active = :active')
            ->orderBy('a.created_at', 'DESC')
            ->setParameter('active', true)
            ->leftjoin('a.user', 'u')
            ->leftjoin('a.forum', 'c')
            ->leftjoin('a.noteSubjects', 'n')
            ->leftjoin('a.comments', 'm')
            ->leftjoin('m.commentLikes', 'k')
            // ->orderBy('avg_note', 'DESC')
        ;

        if (!empty($search->getForum())) {
            $query->andWhere('c.id IN (:cat)')
            ->setParameter('cat', $search->getForum());
        }
        // dd($query->getQuery()->getResult());
        return $this->paginator->paginate(
            $query->getQuery(),
            $search->getPage(),
            10
        );

        // dd($queryBuilder);
    }


   public function findAllSubjectPosted($id,SearchData $search): PaginationInterface
   {
       $query = $this->createQueryBuilder('a')
            ->select('a', 'c',)
            ->andWhere('a.user = :val')
            ->setParameter('val', $id)
            ->leftjoin('a.forum', 'c')
            ->orderBy('a.id', 'DESC')
        ;
        if (!empty($search->getForum())) {
            $query->andWhere('c.id IN (:cat)')
            ->setParameter('cat', $search->getForum());
        }
        // dd($query->getQuery()->getResult());
        return $this->paginator->paginate(
            $query->getQuery(),
            $search->getPage(),
            5
        );
   }

    /**
    * @return Subject[] Returns an array of Subject with same tags
    */
    public function findArticleWithSameForum($forum): array
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.forum', 't')
            ->andWhere('a.active = :active')
            ->andWhere('t.id = :forum')
            ->setParameter('active', true)
            ->setParameter('forum', $forum)
            ->orderBy('a.created_at', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    //       /**
    // * @return Subject[] Returns an array of Subject with same tags
    // */
    // public function findGreatSubjects($id): array
    // {
    //     return $this->createQueryBuilder('a')
    //         ->where('a.user = :id')
    //         ->setParameter('id', $id)
    //         ->orderBy('a.created_at', 'DESC')
    //         ->setMaxResults(5)
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }
    

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
