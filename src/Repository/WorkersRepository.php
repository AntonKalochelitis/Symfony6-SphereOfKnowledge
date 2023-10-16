<?php

namespace App\Repository;

use App\Entity\Workers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Workers>
 *
 * @method Workers|null find($id, $lockMode = null, $lockVersion = null)
 * @method Workers|null findOneBy(array $criteria, array $orderBy = null)
 * @method Workers[]    findAll()
 * @method Workers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Workers::class);
    }

//    /**
//     * @return Workers[] Returns an array of Workers objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Workers
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
