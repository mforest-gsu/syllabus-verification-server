<?php

namespace Gsu\SyllabusVerification\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Gsu\SyllabusVerification\Entity\CourseTemplate;

/**
 * @extends ServiceEntityRepository<CourseTemplate>
 */
final class CourseTemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CourseTemplate::class);
    }
}
