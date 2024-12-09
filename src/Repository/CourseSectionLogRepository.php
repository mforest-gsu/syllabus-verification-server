<?php

namespace Gsu\SyllabusVerification\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Gsu\SyllabusVerification\Entity\CourseSectionLog;

/**
 * @extends ServiceEntityRepository<CourseSectionLog>
 */
final class CourseSectionLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CourseSectionLog::class);
    }
}
