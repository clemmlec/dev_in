<?php

namespace App\Repository;

use App\Filter\SearchData;
use App\Entity\SubjectFavoris;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<SubjectFavoris>
 *
 * @method SubjectFavoris|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubjectFavoris|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubjectFavoris[]    findAll()
 * @method SubjectFavoris[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubjectFavorisRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private PaginatorInterface $paginator
        )
    {
        parent::__construct($registry, SubjectFavoris::class);
    }

    public function add(SubjectFavoris $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SubjectFavoris $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


   public function getSubjectFavoris($id,SearchData $search): PaginationInterface
   {
       $query = $this->createQueryBuilder('a')
            ->select('a', 'u', 'c')
            ->andWhere('a.user = :val')
            ->setParameter('val', $id)
            ->leftjoin('a.subject', 'u')
            ->leftjoin('u.forum', 'c')
            ->orderBy('a.id', 'DESC')
        ;
       if (!empty($search->getForum())) {
            $query->andWhere('c.id IN (:cat)')
            ->setParameter('cat', $search->getForum());
        }

        return $this->paginator->paginate(
            $query->getQuery(),
            $search->getPage(),
            10
        );
   }

}
