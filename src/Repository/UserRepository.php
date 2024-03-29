<?php

namespace App\Repository;

use App\Entity\User;
use App\Filter\SearchData;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Security;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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
        private PaginatorInterface $paginator,
        private Security $security
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
            ->orderBy('u.credibility', 'desc')
        ;

        if (!empty($search->getQuery())) {
            $query->andWhere('u.name LIKE :name')
                ->setParameter('name', "%{$search->getQuery()}%")
            ;
        }
        $result = $this->paginator->paginate(
            $query->getQuery(),
            $search->getPage(),
            6
        );
        $userConected = $this->security->getUser();
        foreach ($result as $user) {
            if($user != $userConected ){
                $user->setPassword('');
                $user->setEmail('');
            }
            
        }
        return $result;
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

    public function findAllUserLastWeek(): array
    {
        $date =date("Y-m-d H:i:s",mktime(0, 0, 0, date("m"), date("d")-6,   date("Y")));
        return $this->createQueryBuilder('u')
            ->andWhere('u.created_at >= :val')
            ->setParameter('val', $date)
            ->select('u.created_at')
            ->groupBy('u.created_at')
            ->getQuery()
            ->getResult()
        ;

    }

}
