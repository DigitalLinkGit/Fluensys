<?php

namespace App\Repository;

use App\Entity\ClassTextareaFieldResponseExtendsFieldResponse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ClassTextareaFieldResponseExtendsFieldResponse>
 */
class ClassTextareaFieldResponseExtendsFieldResponseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClassTextareaFieldResponseExtendsFieldResponse::class);
    }

    //    /**
    //     * @return ClassTextareaFieldResponseExtendsFieldResponse[] Returns an array of ClassTextareaFieldResponseExtendsFieldResponse objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ClassTextareaFieldResponseExtendsFieldResponse
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
