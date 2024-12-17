<?php

declare(strict_types=1);

namespace Gsu\SyllabusVerification\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gsu\SyllabusVerification\Repository\CourseSectionRepository;

#[ORM\Entity(CourseSectionRepository::class)]
#[ORM\Table('CoreImpactsCourseSection')]
#[ORM\UniqueConstraint('udxCoreImpactsCourseSection_01', ['TermCode', 'CRN'])]
#[ORM\Index('idxCoreImpactsCourseSection_01', ['TermCode', 'CollegeCode', 'DepartmentCode'])]
#[ORM\Index('idxCoreImpactsCourseSection_02', ['CRN'])]
#[ORM\Index('idxCoreImpactsCourseSection_03', ['SubjectCode', 'CourseNumber', 'SequenceNumber'])]
#[ORM\Index('idxCoreImpactsCourseSection_04', ['CourseTitle'])]
#[ORM\Index('idxCoreImpactsCourseSection_05', ['InstructorFirstName', 'InstructorLastName'])]
#[ORM\Index('idxCoreImpactsCourseSection_06', ['InstructorEmail'])]
#[ORM\Index('idxCoreImpactsCourseSection_07', ['VerifyStatus'])]
#[ORM\Index('idxCoreImpactsCourseSection_08', ['VerifyDate'])]
#[ORM\Index('idxCoreImpactsCourseSection_09', ['VerifyUser'])]
class CourseSection implements \JsonSerializable
{
    #region Constants

    public const SET_VALUES_SET_KEYS = 1;
    public const SET_VALUES_SET_VERIFY = 2;
    public const IS_EQUAL_ONLY_KEYS = 4;
    public const IS_EQUAL_INCLUDE_VERIFY = 8;

    #endregion


    #region Column Definitions

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int|null $id = null;
    public function getId(): int|null
    {
        return $this->id;
    }
    public function setId(int|null $id): static
    {
        $this->id = $id;
        return $this;
    }


    #[ORM\Column(name: 'TermCode', type: Types::STRING, length: 6)]
    private string $TermCode = "";
    public function getTermCode(): string
    {
        return $this->TermCode;
    }
    public function setTermCode(string $TermCode): static
    {
        $this->TermCode = $TermCode;
        return $this;
    }


    #[ORM\Column(name: 'TermDescription', type: Types::STRING, length: 64)]
    private string $TermDescription = "";
    public function getTermDescription(): string
    {
        return $this->TermDescription;
    }
    public function setTermDescription(string $TermDescription): static
    {
        $this->TermDescription = $TermDescription;
        return $this;
    }


    #[ORM\Column(name: 'CollegeCode', type: Types::STRING, length: 4)]
    private string $CollegeCode = "";
    public function getCollegeCode(): string
    {
        return $this->CollegeCode;
    }
    public function setCollegeCode(string $CollegeCode): static
    {
        $this->CollegeCode = $CollegeCode;
        return $this;
    }


    #[ORM\Column(name: 'CollegeDescription', type: Types::STRING, length: 64)]
    private string $CollegeDescription = "";
    public function getCollegeDescription(): string
    {
        return $this->CollegeDescription;
    }
    public function setCollegeDescription(string $CollegeDescription): static
    {
        $this->CollegeDescription = $CollegeDescription;
        return $this;
    }


    #[ORM\Column(name: 'DepartmentCode', type: Types::STRING, length: 4)]
    private string $DepartmentCode = "";
    public function getDepartmentCode(): string
    {
        return $this->DepartmentCode;
    }
    public function setDepartmentCode(string $DepartmentCode): static
    {
        $this->DepartmentCode = $DepartmentCode;
        return $this;
    }


    #[ORM\Column(name: 'DepartmentDescription', type: Types::STRING, length: 64)]
    private string $DepartmentDescription = "";
    public function getDepartmentDescription(): string
    {
        return $this->DepartmentDescription;
    }
    public function setDepartmentDescription(string $DepartmentDescription): static
    {
        $this->DepartmentDescription = $DepartmentDescription;
        return $this;
    }


    #[ORM\Column(name: 'CRN', type: Types::STRING, length: 5)]
    private string $CRN = "";
    public function getCRN(): string
    {
        return $this->CRN;
    }
    public function setCRN(string $CRN): static
    {
        $this->CRN = $CRN;
        return $this;
    }


    #[ORM\Column(name: 'SubjectCode', type: Types::STRING, length: 4)]
    private string $SubjectCode = "";
    public function getSubjectCode(): string
    {
        return $this->SubjectCode;
    }
    public function setSubjectCode(string $SubjectCode): static
    {
        $this->SubjectCode = $SubjectCode;
        return $this;
    }


    #[ORM\Column(name: 'CourseNumber', type: Types::STRING, length: 5)]
    private string $CourseNumber = "";
    public function getCourseNumber(): string
    {
        return $this->CourseNumber;
    }
    public function setCourseNumber(string $CourseNumber): static
    {
        $this->CourseNumber = $CourseNumber;
        return $this;
    }


    #[ORM\Column(name: 'SequenceNumber', type: Types::STRING, length: 3,)]
    private string $SequenceNumber = "";
    public function getSequenceNumber(): string
    {
        return $this->SequenceNumber;
    }
    public function setSequenceNumber(string $SequenceNumber): static
    {
        $this->SequenceNumber = $SequenceNumber;
        return $this;
    }


    #[ORM\Column(name: 'CourseTitle', type: Types::STRING, length: 64)]
    private string $CourseTitle = "";
    public function getCourseTitle(): string
    {
        return $this->CourseTitle;
    }
    public function setCourseTitle(string $CourseTitle): static
    {
        $this->CourseTitle = $CourseTitle;
        return $this;
    }


    #[ORM\Column(name: 'InstructorFirstName', type: Types::STRING, length: 120, nullable: true)]
    private string|null $InstructorFirstName = null;
    public function getInstructorFirstName(): string|null
    {
        return $this->InstructorFirstName;
    }
    public function setInstructorFirstName(string|null $InstructorFirstName): static
    {
        $this->InstructorFirstName = $InstructorFirstName;
        return $this;
    }


    #[ORM\Column(name: 'InstructorLastName', type: Types::STRING, length: 120, nullable: true)]
    private string|null $InstructorLastName = null;
    public function getInstructorLastName(): string|null
    {
        return $this->InstructorLastName;
    }
    public function setInstructorLastName(string|null $InstructorLastName): static
    {
        $this->InstructorLastName = $InstructorLastName;
        return $this;
    }


    #[ORM\Column(name: 'InstructorEmail', type: Types::STRING, length: 30, nullable: true)]
    private string|null $InstructorEmail = null;
    public function getInstructorEmail(): string|null
    {
        return $this->InstructorEmail;
    }
    public function setInstructorEmail(string|null $InstructorEmail): static
    {
        $this->InstructorEmail = $InstructorEmail;
        return $this;
    }


    #[ORM\Column(name: 'VerifyStatus', type: Types::STRING, length: 10)]
    private string $VerifyStatus = "Unverified";
    public function getVerifyStatus(): string
    {
        return $this->VerifyStatus;
    }
    public function setVerifyStatus(string $VerifyStatus): static
    {
        $this->VerifyStatus = $VerifyStatus;
        return $this;
    }


    #[ORM\Column(name: 'VerifyDate', type: Types::DATETIME_MUTABLE, nullable: true)]
    private \DateTimeInterface|null $VerifyDate = null;
    public function getVerifyDate(): \DateTimeInterface|null
    {
        return $this->VerifyDate;
    }
    public function setVerifyDate(\DateTimeInterface|null $VerifyDate): static
    {
        $this->VerifyDate = $VerifyDate;
        return $this;
    }


    #[ORM\Column(name: 'VerifyUser', type: Types::STRING, length: 30, nullable: true)]
    private string|null $VerifyUser = null;
    public function getVerifyUser(): string|null
    {
        return $this->VerifyUser;
    }
    public function setVerifyUser(string|null $VerifyUser): static
    {
        $this->VerifyUser = $VerifyUser;
        return $this;
    }


    public function getCourseCode(): string
    {
        return implode(" ", array_filter([
            $this->getSubjectCode(),
            $this->getCourseNumber(),
            $this->getSequenceNumber()
        ], fn($v) => $v !== ''));
    }


    public function getInstructorName(): string
    {
        return implode(" ", array_filter([
            $this->getInstructorFirstName(),
            $this->getInstructorLastName(),
            is_string($this->getInstructorEmail())
                ? sprintf("<%s>", $this->getInstructorEmail())
                : null
        ], fn($v) => $v !== null && $v !== ''));
    }

    #endregion Column Definition


    #region Values Methods

    /**
     * @return array<string,mixed>
     */
    public function getValues(): array
    {
        return [
            'id'                    => $this->getId(),
            'TermCode'              => $this->getTermCode(),
            'TermDescription'       => $this->getTermDescription(),
            'CollegeCode'           => $this->getCollegeCode(),
            'CollegeDescription'    => $this->getCollegeDescription(),
            'DepartmentCode'        => $this->getDepartmentCode(),
            'DepartmentDescription' => $this->getDepartmentDescription(),
            'CRN'                   => $this->getCRN(),
            'SubjectCode'           => $this->getSubjectCode(),
            'CourseNumber'          => $this->getCourseNumber(),
            'SequenceNumber'        => $this->getSequenceNumber(),
            'CourseTitle'           => $this->getCourseTitle(),
            'InstructorFirstName'   => $this->getInstructorFirstName(),
            'InstructorLastName'    => $this->getInstructorLastName(),
            'InstructorEmail'       => $this->getInstructorEmail(),
            'VerifyStatus'          => $this->getVerifyStatus(),
            'VerifyDate'            => $this->getVerifyDate(),
            'VerifyUser'            => $this->getVerifyUser(),
        ];
    }


    /**
     * @param array<string,mixed>|self $values
     * @param int $options
     * @return self
     */
    public function setValues(
        array|self $values,
        int $options = 0
    ): self {
        $setKeys = ($options & self::SET_VALUES_SET_KEYS) === self::SET_VALUES_SET_KEYS;
        $setVerify = ($options & self::SET_VALUES_SET_VERIFY) === self::SET_VALUES_SET_VERIFY;

        if ($values instanceof self) {
            $values = $values->getValues();
        }

        $v = new EntityValidators(self::class, $values);

        if ($setKeys) {
            $this
                ->setTermCode($v->string('TermCode'))
                ->setCRN($v->string('CRN'));
        }

        if ($setVerify) {
            $this
                ->setVerifyStatus($v->string('VerifyStatus'))
                ->setVerifyDate($v->objectNull(
                    'VerifiedOn',
                    \DateTimeInterface::class,
                    fn (mixed $v): \DateTimeInterface|null => is_scalar($v) ? new \DateTime(strval($v)) : null
                ))
                ->setVerifyUser($v->stringNull('VerifyUser'));
        }

        return $this
            ->setTermDescription($v->string('TermDescription'))
            ->setCollegeCode($v->string('CollegeCode'))
            ->setCollegeDescription($v->string('CollegeDescription'))
            ->setDepartmentCode($v->string('DepartmentCode'))
            ->setDepartmentDescription($v->string('DepartmentDescription'))
            ->setSubjectCode($v->string('SubjectCode'))
            ->setCourseNumber($v->string('CourseNumber'))
            ->setSequenceNumber($v->string('SequenceNumber'))
            ->setCourseTitle($v->string('CourseTitle'))
            ->setInstructorFirstName($v->stringNull('InstructorFirstName'))
            ->setInstructorLastName($v->stringNull('InstructorLastName'))
            ->setInstructorEmail($v->stringNull('InstructorEmail'));
    }


    /**
     * @param self $b
     * @param int $options
     * @return bool
     */
    public function isEqual(
        self $b,
        int $options = 0
    ): bool {
        $a = $this;
        $onlyKeys = ($options & self::IS_EQUAL_ONLY_KEYS) === self::IS_EQUAL_ONLY_KEYS;
        $includeVerify = ($options & self::IS_EQUAL_INCLUDE_VERIFY) === self::IS_EQUAL_INCLUDE_VERIFY;

        $keysAreEqual = (
            $a->getTermCode() === $b->getTermCode() &&
            $a->getCRN() === $b->getCRN()
        );

        return $keysAreEqual && ($onlyKeys || (
            $a->getTermDescription() === $b->getTermDescription() &&
            $a->getCollegeCode() === $b->getCollegeCode() &&
            $a->getCollegeDescription() === $b->getCollegeDescription() &&
            $a->getDepartmentCode() === $b->getDepartmentCode() &&
            $a->getDepartmentDescription() === $b->getDepartmentDescription() &&
            $a->getSubjectCode() === $b->getSubjectCode() &&
            $a->getCourseNumber() === $b->getCourseNumber() &&
            $a->getSequenceNumber() === $b->getSequenceNumber() &&
            $a->getCourseTitle() === $b->getCourseTitle() &&
            $a->getInstructorFirstName() === $b->getInstructorFirstName() &&
            $a->getInstructorLastName() === $b->getInstructorLastName() &&
            $a->getInstructorEmail() === $b->getInstructorEmail() &&
            (
                !$includeVerify || (
                    $a->getVerifyStatus() === $b->getVerifyStatus() &&
                    ($a->getVerifyDate()?->getTimestamp() === $b->getVerifyDate()?->getTimestamp()) &&
                    $a->getVerifyStatus() === $b->getVerifyStatus()
                )
            )
        ));
    }

    #endregion Values Methods


    /**
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return $this->getValues();
    }


    /**
     * @param array<string,mixed> $values
     */
    public function __construct(array|null $values = null)
    {
        if (is_array($values)) {
            $this->setValues($values, self::SET_VALUES_SET_KEYS);
        }
    }
}
