<?php

namespace Gsu\SyllabusVerification\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Gsu\SyllabusVerification\Entity\LabelOverride;

/**
 * @extends ServiceEntityRepository<LabelOverride>
 */
final class LabelOverrideRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LabelOverride::class);
    }
}
