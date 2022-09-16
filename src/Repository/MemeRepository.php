<?php

namespace App\Repository;

use App\Entity\Meme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Meme>
 *
 * @method Meme|null find($id, $lockMode = null, $lockVersion = null)
 * @method Meme|null findOneBy(array $criteria, array $orderBy = null)
 * @method Meme[]    findAll()
 * @method Meme[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MemeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Meme::class);
    }

    public function add(Meme $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Meme $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findActiveMeme()
    {
        return $this->createQueryBuilder('a')
            ->select('a', 'u', 'c', 'n', 'm', 'k')
            ->andWhere('a.visible = :active')
            ->setParameter('active', true)
            ->leftjoin('a.user_id', 'u')
            ->leftjoin('a.categorie_id', 'c')
            ->leftjoin('a.noteMemes', 'n')
            ->leftjoin('a.comments', 'm')
            ->leftjoin('m.commentLikes', 'k')
            ->orderBy('a.created_at', 'DESC')
            ->getQuery()
            ->getResult()
        ;
        // dd($queryBuilder);
    }

//    /**
//     * @return Meme[] Returns an array of Meme objects
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

//    public function findOneBySomeField($value): ?Meme
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
