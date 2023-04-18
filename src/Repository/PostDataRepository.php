<?php

namespace App\Repository;

use App\Entity\PostData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PostData>
 *
 * @method PostData|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostData|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostData[]    findAll()
 * @method PostData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostData::class);
    }

    public function save(PostData $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PostData $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findAll()
    {
        return $this->findBy(array(), array('postId' => 'DESC'));
    }
    public function findAllByPostAuthor($value): array
    {
        return $this->createQueryBuilder('post')
            ->andWhere('post.postAuthor = :val')
            ->setParameter('val', $value)
            ->addOrderBy('post.postId', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return PostData[] Returns an array of PostData objects
//     */


//    public function findOneBySomeField($value): ?PostData
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
