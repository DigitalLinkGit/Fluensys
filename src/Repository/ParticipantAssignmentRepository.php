<?php
namespace App\Repository;

use App\Entity\ParticipantAssignment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ParticipantAssignment>
 *
 * @method ParticipantAssignment|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParticipantAssignment|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParticipantAssignment[]    findAll()
 * @method ParticipantAssignment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipantAssignmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParticipantAssignment::class);
    }

    // Add custom methods here if needed
}
