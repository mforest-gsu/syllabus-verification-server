<?php

namespace Gsu\SyllabusVerification\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;
use Gsu\SyllabusVerification\Entity\CourseSection;
use Gsu\SyllabusVerification\Entity\FetchCourseSectionsParameters;
use Gsu\SyllabusVerification\Entity\FilterValue;

/**
 * @extends ServiceEntityRepository<CourseSection>
 */
final class CourseSectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CourseSection::class);
    }


    /**
     * @return array{count:int,data:CourseSection[]}
     */
    public function fetchCourseSections(
        FetchCourseSectionsParameters $params,
        int|null $offset = null,
        int|null $limit = null,
        string $orderBy = 'CRN'
    ): array {
        $query = $this
            ->createQueryBuilder('s')
            ->setFirstResult($offset)
            ->setMaxResults($limit);
        $expr = $query->expr();

        $queryWhere = [$expr->eq('s.TermCode', ':termCode')];
        $queryParam = new ArrayCollection([new Parameter('termCode', $params->TermCode)]);

        if (is_string($params->CollegeCode) && $params->CollegeCode !== "") {
            $queryWhere[] = $expr->eq('s.CollegeCode', ':CollegeCode');
            $queryParam->add(new Parameter('CollegeCode', $params->CollegeCode));
        }
        if (is_string($params->DepartmentCode) && $params->DepartmentCode !== "") {
            $queryWhere[] = $expr->eq('s.DepartmentCode', ':DepartmentCode');
            $queryParam->add(new Parameter('DepartmentCode', $params->DepartmentCode));
        }
        if (is_string($params->CRN) && $params->CRN !== "") {
            $queryWhere[] = $expr->like('s.CRN', ':CRN');
            $queryParam->add(new Parameter('CRN', $params->CRN . '%'));
        }
        if (is_string($params->SubjectCode) && $params->SubjectCode !== "") {
            $queryWhere[] = $expr->like('s.SubjectCode', ':SubjectCode');
            $queryParam->add(new Parameter('SubjectCode', $params->SubjectCode . '%'));
        }
        if (is_string($params->CourseNumber) && $params->CourseNumber !== "") {
            $queryWhere[] = $expr->like('s.CourseNumber', ':CourseNumber');
            $queryParam->add(new Parameter('CourseNumber', $params->CourseNumber . '%'));
        }
        if (is_string($params->SequenceNumber) && $params->SequenceNumber !== "") {
            $queryWhere[] = $expr->like('s.SequenceNumber', ':SequenceNumber');
            $queryParam->add(new Parameter('SequenceNumber', $params->SequenceNumber . '%'));
        }
        if (is_string($params->CourseTitle) && $params->CourseTitle !== "") {
            $queryWhere[] = $expr->like('s.CourseTitle', ':CourseTitle');
            $queryParam->add(new Parameter('CourseTitle', '%' . $params->CourseTitle . '%'));
        }
        if (is_string($params->InstructorFirstName) && $params->InstructorFirstName !== "") {
            $queryWhere[] = $expr->like('s.InstructorFirstName', ':InstructorFirstName');
            $queryParam->add(new Parameter('InstructorFirstName', '%' . $params->InstructorFirstName . '%'));
        }
        if (is_string($params->InstructorLastName) && $params->InstructorLastName !== "") {
            $queryWhere[] = $expr->like('s.InstructorLastName', ':InstructorLastName');
            $queryParam->add(new Parameter('InstructorLastName', '%' . $params->InstructorLastName . '%'));
        }
        if (is_string($params->InstructorEmail) && $params->InstructorEmail !== "") {
            $queryWhere[] = $expr->like('s.InstructorEmail', ':InstructorEmail');
            $queryParam->add(new Parameter('InstructorEmail', '%' . $params->InstructorEmail . '%'));
        }
        if (is_string($params->VerifyStatus) && $params->VerifyStatus !== "") {
            $queryWhere[] = $expr->eq('s.VerifyStatus', ':VerifyStatus');
            $queryParam->add(new Parameter('VerifyStatus', $params->VerifyStatus));
        }
        if (is_string($params->VerifyDateStart) && $params->VerifyDateStart !== "") {
            $queryWhere[] = $expr->gte('s.VerifyDate', ':VerifyDateStart');
            $queryParam->add(new Parameter('VerifyDateStart', $params->VerifyDateStart));
        }
        if (is_string($params->VerifyDateEnd) && $params->VerifyDateEnd !== "") {
            $queryWhere[] = $expr->lte('s.VerifyDate', ':VerifyDateEnd');
            $queryParam->add(new Parameter('VerifyDateEnd', $params->VerifyDateEnd));
        }
        if (is_string($params->VerifyUser) && $params->VerifyUser !== "") {
            $queryWhere[] = $expr->like('s.VerifyUser', ':VerifyUser');
            $queryParam->add(new Parameter('VerifyUser', $params->VerifyUser . '%'));
        }

        $query
            ->where($queryWhere)
            ->setParameters($queryParam);

        if ($orderBy !== "") {
            array_map(
                fn($v) => $query->addOrderBy(...$v),
                array_map(
                    fn($v) => ["s." . $v[0], $v[1] ?? null],
                    array_map(
                        fn($v) => explode(" ", $v),
                        explode(",", $orderBy)
                    )
                )
            );
        }

        /** @var CourseSection[] $data */
        $data = $query
            ->getQuery()
            ->getResult();

        /** @var int $count */
        $count = $query
            ->select('count(1)')
            ->setFirstResult(null)
            ->setMaxResults(null)
            ->getQuery()
            ->getSingleScalarResult();

        return ['count' => $count, 'data' => $data];
    }


    /**
     * @return string
     */
    public function fetchCurrentTermCode(): string|null
    {
        /** @var string|null $termCode */
        $termCode = $this
            ->createQueryBuilder('s')
            ->select('max(s.TermCode)')
            ->getQuery()
            ->getSingleScalarResult();
        return $termCode;
    }


    /**
     * @return FilterValue[]
     */
    public function fetchFilterValues(): array
    {
        $termCode = $this->fetchCurrentTermCode();
        if ($termCode === null) {
            return [];
        }

        /** @var array<string,string>[] $result */
        $result = $this
            ->createQueryBuilder('s')
            ->select(
                's.TermCode',
                's.TermDescription',
                's.CollegeCode',
                's.CollegeDescription',
                's.DepartmentCode',
                's.DepartmentDescription'
            )
            ->distinct()
            ->where('s.TermCode = :termCode')
            ->addOrderBy('s.TermCode', 'DESC')
            ->addOrderBy('s.CollegeDescription')
            ->addOrderBy('s.DepartmentDescription')
            ->setParameter('termCode', $termCode)
            ->getQuery()
            ->getResult();

        /** @var FilterValue[] $filters */
        $filters = [];
        foreach ($result as $record) {
            $termCode = $record['TermCode'] ?? '';
            if (!isset($filters[$termCode])) {
                $filters[$termCode] = new FilterValue(
                    'Term',
                    $termCode,
                    $record['TermDescription'] ?? ''
                );
            }
            $term = $filters[$termCode];

            $collegeCode = $record['CollegeCode'] ?? '';
            if (!isset($term->children[$collegeCode])) {
                $term->children[$collegeCode] = new FilterValue(
                    'College',
                    $collegeCode,
                    $record['CollegeDescription'] ?? '',
                );
            }
            $college = $term->children[$collegeCode];

            $departmentCode = $record['DepartmentCode'] ?? '';
            if (!isset($college->children[$departmentCode])) {
                $college->children[$departmentCode] = new FilterValue(
                    'Department',
                    $departmentCode,
                    $record['DepartmentDescription'] ?? ''
                );
            }
        }

        return array_values($filters);
    }
}
