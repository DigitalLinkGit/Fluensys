<?php
// src/Repository/ActivityLogRepository.php
namespace App\Repository;

use App\Entity\ActivityLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class ActivityLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivityLog::class);
    }

    /**
     * @return ActivityLog[]
     */
    public function findForContext(
        ?int $accountId,
        ?int $projectId,
        ?int $captureId,
        ?int $captureElementId,
        ?string $action,
        int $limit = 200,
    ): array {
        $qb = $this->createQueryBuilder('l')
            ->leftJoin('l.account', 'a')->addSelect('a')
            ->leftJoin('l.project', 'p')->addSelect('p')
            ->leftJoin('l.capture', 'c')->addSelect('c')
            ->leftJoin('l.captureElement', 'ce')->addSelect('ce')
            ->orderBy('l.occurredAt', 'DESC')
            ->setMaxResults($limit);

        if (null !== $accountId) {
            $qb->andWhere('l.account = :accountId')
                ->setParameter('accountId', $accountId);
        }

        if (null !== $projectId) {
            $qb->andWhere('l.project = :projectId')->setParameter('projectId', $projectId);
        }
        if (null !== $captureId) {
            $qb->andWhere('l.capture = :captureId')->setParameter('captureId', $captureId);
        }
        if (null !== $captureElementId) {
            $qb->andWhere('l.captureElement = :captureElementId')->setParameter('captureElementId', $captureElementId);
        }
        if (null !== $action && '' !== $action) {
            $qb->andWhere('l.action = :action')->setParameter('action', $action);
        }

        return $qb->getQuery()->getResult();
    }

}
