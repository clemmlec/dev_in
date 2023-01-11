<?php

namespace App\Repository;

use App\Entity\CommentLike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommentLike>
 *
 * @method CommentLike|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentLike|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentLike[]    findAll()
 * @method CommentLike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentLikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentLike::class);
    }

    public function add(CommentLike $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CommentLike $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

}
