<?php

declare(strict_types=1);

namespace Gsu\SyllabusVerification\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Gsu\SyllabusVerification\Entity\CourseSection;
use Gsu\SyllabusVerification\Entity\CourseSectionLog;
use Gsu\SyllabusVerification\Entity\FetchCourseSectionsParameters;
use Gsu\SyllabusVerification\Entity\SelectedCourseSections;
use Gsu\SyllabusVerification\Repository\CourseSectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class CourseSectionController extends AbstractController
{
    /**
     * @param EntityManagerInterface $entityManager
     * @param CourseSectionRepository $courseSectionRepo
     */
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CourseSectionRepository $courseSectionRepo
    ) {
    }


    #[Route(methods: 'GET', path: '/courses', format: 'json')]
    public function getCourseSections(
        #[MapQueryString] FetchCourseSectionsParameters $params = new FetchCourseSectionsParameters(),
        #[MapQueryParameter] int $offset = 0,
        #[MapQueryParameter] int $limit = 25,
        #[MapQueryParameter] string $orderBy = "CRN"
    ): JsonResponse {
        $params->CollegeCode ??= "NONE";
        $params->DepartmentCode ??= "NONE";

        return $this->json($this->courseSectionRepo->fetchCourseSections(
            $params,
            $offset,
            $limit,
            $orderBy
        ));
    }


    /**
     * @param SelectedCourseSections $payload
     * @return JsonResponse
     */
    #[Route(methods: 'PUT', path: '/courses/verify', format: 'json')]
    public function verifyCourseSections(
        #[MapRequestPayload] SelectedCourseSections $payload
    ): JsonResponse {
        return $this->json($this->setVerifyStatus(
            '0', // userId
            $payload->selected,
            'Verified'
        ));
    }


    /**
     * @param SelectedCourseSections $payload
     * @return JsonResponse
     */
    #[Route(methods: 'PUT', path: '/courses/unverify', format: 'json')]
    public function unverifyCourseSections(
        #[MapRequestPayload] SelectedCourseSections $payload
    ): JsonResponse {
        return $this->json($this->setVerifyStatus(
            '0', // userId
            $payload->selected,
            'Unverified'
        ));
    }


    /**
     * @param string $userId
     * @param int[] $selected
     * @param string $status
     * @return bool
     */
    private function setVerifyStatus(
        string $userId,
        array $selected,
        string $status
    ): bool {
        $i = 0;
        foreach ($selected as $id) {
            $courseSection = $this->courseSectionRepo->findOneBy(['id' => $id]);
            if (!$courseSection instanceof CourseSection || $courseSection->getVerifyStatus() === $status) {
                continue;
            }

            if ($status === 'Verified') {
                $courseSection
                    ->setVerifyStatus("Verified")
                    ->setVerifyDate(new \DateTimeImmutable())
                    ->setVerifyUser($userId);
            } elseif ($status === 'Unverified') {
                $courseSection
                    ->setVerifyStatus("Unverified")
                    ->setVerifyDate(null)
                    ->setVerifyUser(null);
            }

            $this->entityManager->persist(new CourseSectionLog([
                ...$courseSection->getValues(),
                'LogTimestamp' => new \DateTimeImmutable(),
                'LogUser' => $userId
            ]));

            if (++$i % 100 === 0) {
                $this->entityManager->flush();
            }
        }

        if ($i > 0) {
            $this->entityManager->flush();
        }

        return true;
    }
}
