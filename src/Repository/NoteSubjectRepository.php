<?php

namespace App\Repository;

use App\Filter\SearchData;
use App\Entity\NoteSubject;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<NoteSubject>
 *
 * @method NoteSubject|null find($id, $lockMode = null, $lockVersion = null)
 * @method NoteSubject|null findOneBy(array $criteria, array $orderBy = null)
 * @method NoteSubject[]    findAll()
 * @method NoteSubject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoteSubjectRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private PaginatorInterface $paginator
        )
    {
        parent::__construct($registry, NoteSubject::class);
    }

    public function add(NoteSubject $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(NoteSubject $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return NoteSubject[] Returns an array of NoteSubject objects
    */
   public function getSubjectNoter($id,SearchData $search): PaginationInterface
   {
        $query = $this->createQueryBuilder('n')
            ->select('n', 'u', 'c')
            ->andWhere('n.user = :val')
            ->setParameter('val', $id)
            ->leftjoin('n.subject', 'u')
            ->leftjoin('u.forum', 'c')
            ->orderBy('n.id', 'DESC')
        ;
        if (!empty($search->getForum())) {
            $query->andWhere('c.id IN (:cat)')
            ->setParameter('cat', $search->getForum());
        }

        return $this->paginator->paginate(
            $query->getQuery(),
            $search->getPage(),
            5
        );
   }

//    public function findOneBySomeField($value): ?NoteSubject
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
