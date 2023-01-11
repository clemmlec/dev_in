<?php

namespace App\Repository;

use App\Entity\Follow;
use App\Filter\SearchData;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
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
        private PaginatorInterface $paginator
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


   public function findAllFollowed($id,SearchData $search): PaginationInterface
   {
       $query =  $this->createQueryBuilder('f')
            ->select('f','c')
           ->andWhere('f.user = :val')
           ->setParameter('val', $id)
           ->leftjoin('f.friend', 'c')
           ->orderBy('f.id', 'DESC')
       ;
       if (!empty($search->getQuery())) {
        $query->andWhere('c.name LIKE :name')
            ->setParameter('name', "%{$search->getQuery()}%")
        ;
        }

        return $this->paginator->paginate(
            $query->getQuery(),
            $search->getPage(),
            10
        );
   }


    public function findAllFollowers($id,SearchData $search): PaginationInterface
    {
        $query= $this->createQueryBuilder('f')
            ->select('f','c')
            ->andWhere('f.friend = :val')
            ->setParameter('val', $id)
            ->leftjoin('f.user', 'c')
            ->orderBy('f.id', 'DESC')
        ;
        if (!empty($search->getQuery())) {
            $query->andWhere('c.name LIKE :name')
                ->setParameter('name', "%{$search->getQuery()}%")
        ;
        }

        return $this->paginator->paginate(
            $query->getQuery(),
            $search->getPage(),
            5
        );
    }


}

