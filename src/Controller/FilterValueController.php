<?php

declare(strict_types=1);

namespace Gsu\SyllabusVerification\Controller;

use Gsu\SyllabusVerification\Entity\FilterValue;
use Gsu\SyllabusVerification\Repository\CourseSectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class FilterValueController extends AbstractController
{
    /**
     * @param CourseSectionRepository $courseSectionRepo
     */
    public function __construct(private CourseSectionRepository $courseSectionRepo)
    {
    }


    #[Route(methods: 'GET', path: '/filters', format: 'json')]
    public function getTerms(): JsonResponse
    {
        return $this->json([
            'Term' => $this->courseSectionRepo->fetchFilterValues(),
            'Status' => [
                new FilterValue('Status', 'Unverified', 'Unverified'),
                new FilterValue('Status', 'Verified', 'Verified'),
            ]
        ]);
    }
}
