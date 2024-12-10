<?php

declare(strict_types=1);

namespace Gsu\SyllabusVerification\Command;

use Doctrine\ORM\EntityManagerInterface;
use Gadget\Io\Cast;
use Gsu\SyllabusVerification\Entity\CourseSection;
use Gsu\SyllabusVerification\Entity\CourseSectionLog;
use Gsu\SyllabusVerification\Entity\CourseTemplate;
use Gsu\SyllabusVerification\Entity\FetchCourseSectionsParameters;
use Gsu\SyllabusVerification\Query\GetCourseSectionsQuery;
use Gsu\SyllabusVerification\Query\GetCurrentTermQuery;
use Gsu\SyllabusVerification\Repository\BannerRepository;
use Gsu\SyllabusVerification\Repository\CourseSectionRepository;
use Gsu\SyllabusVerification\Repository\CourseTemplateRepository;
use Gsu\SyllabusVerification\Repository\LabelOverrideRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('app:sync-course-sections')]
final class SyncCourseSectionsCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private BannerRepository $bannerRepo,
        private CourseTemplateRepository $courseTemplateRepo,
        private CourseSectionRepository $courseSectionRepo,
        private LabelOverrideRepository $labelOverrideRepo
    ) {
        parent::__construct();
    }


    /** @inheritdoc */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $templates = $this->fetchCourseTemplates();
        $output->writeln(sprintf(
            "Fetched %d course templates",
            count($templates)
        ));

        list($currentTerm, $bannerSections) = $this->fetchBannerSections($templates);
        $output->writeln(sprintf(
            "Fetched %d Banner course sections for term '%s'",
            count($bannerSections),
            $currentTerm
        ));

        $localSections = $this->fetchLocalSections($currentTerm);
        $output->writeln(sprintf(
            "Fetched %d local course sections for term '%s'",
            count($localSections),
            $currentTerm
        ));

        list($created, $updated, $deleted) = $this->syncCourseSections(
            $bannerSections,
            $localSections
        );
        $output->writeln(sprintf(
            "Sync results: %d => %d; +%d, ~%d, -%d",
            count($localSections),
            count($bannerSections),
            $created,
            $updated,
            $deleted
        ));

        return self::SUCCESS;
    }


    /**
     * @return array<string,int>
     */
    private function fetchCourseTemplates(): array
    {
        return array_flip(array_unique(array_map(
            fn (CourseTemplate $template): string => $template->getSubjectCode() . $template->getCourseNumber(),
            $this->courseTemplateRepo->findAll()
        )));
    }


    /**
     * @param array<string,int> $templates
     * @return array{0:string,1:array<string,CourseSection>}
     */
    private function fetchBannerSections(array &$templates): array
    {
        $termCode = $this->bannerRepo->fetch(
            new GetCurrentTermQuery(),
            fn (array $values) => Cast::toString($values['STVTERM_CODE'])
        )[0] ?? "";

        $sections = $this->bannerRepo->fetch(
            new GetCourseSectionsQuery($termCode),
            fn (array $values) => new CourseSection($values)
        );

        $sections = array_filter(
            $sections,
            fn (CourseSection $s): bool => isset($templates[$s->getSubjectCode() . $s->getCourseNumber()])
        );

        $sections = array_map(
            fn (CourseSection $s): CourseSection => $s->setCollegeDescription(
                $this->labelOverrideRepo->findOneBy([
                    'LabelCategory' => 'STVCOLL',
                    'LabelCode' => $s->getCollegeCode()
                ])?->getLabelValue() ?? $s->getCollegeDescription()
            ),
            $sections
        );

        $sections = $this->indexSectionList($sections);

        return [
            $termCode,
            $sections
        ];
    }


    /**
     * @param string $currentTerm
     * @return array<string,CourseSection>
     */
    private function fetchLocalSections(string $currentTerm): array
    {
        list('data' => $sections) = $this->courseSectionRepo->fetchCourseSections(
            new FetchCourseSectionsParameters($currentTerm)
        );
        return $this->indexSectionList($sections);
    }


    /**
     * @param CourseSection[] $sections
     * @return array<string,CourseSection>
     */
    private function indexSectionList(array $sections): array
    {
        return array_column(
            array_map(
                fn (CourseSection $s) => [$s, $s->getCRN()],
                $sections
            ),
            0,
            1
        );
    }


    /**
     * @param array<string,CourseSection> $bannerSections
     * @param array<string,CourseSection> $localSections
     * @return array{0:int,1:int,2:int}
     */
    private function syncCourseSections(
        array &$bannerSections,
        array &$localSections
    ): array {
        $created = $updated = $deleted = 0;

        foreach ($bannerSections as $bannerSection) {
            $localSection = $localSections[$bannerSection->getCRN()] ?? null;
            if ($localSection === null) {
                $this->entityManager->persist($bannerSection);
                $this->entityManager->persist(new CourseSectionLog([
                    ...$bannerSection->getValues(),
                    'LogTimestamp' => new \DateTime(),
                    'LogUser' => 'system'
                ]));
                if ((++$created + $updated) % 250 === 0) {
                    $this->entityManager->flush();
                }
            } elseif (!$localSection->isEqual($bannerSection)) {
                $localSection->setValues($bannerSection);
                if ((++$updated + $created) % 250 === 0) {
                    $this->entityManager->flush();
                }
            }
        }

        foreach ($localSections as $localSection) {
            if (!isset($bannerSections[$localSection->getCRN()])) {
                $this->entityManager->remove($localSection);
                if (++$deleted % 250 === 0) {
                    $this->entityManager->flush();
                }
            }
        }

        $this->entityManager->flush();

        return [$created, $updated, $deleted];
    }
}
