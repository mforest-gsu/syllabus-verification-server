<?php

declare(strict_types=1);

namespace Gsu\SyllabusVerification\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gsu\SyllabusVerification\Repository\CourseTemplateRepository;

#[ORM\Entity(CourseTemplateRepository::class)]
#[ORM\Table('CoreImpactsCourseTemplate')]
#[ORM\UniqueConstraint('udxCoreImpactsCourseTemplate_01', ['ImpactAreaCode', 'SubjectCode', 'CourseNumber'])]
#[ORM\Index('idxCoreImpactsCourseTemplate_01', ['ImpactAreaCode'])]
#[ORM\Index('idxCoreImpactsCourseTemplate_02', ['SubjectCode', 'CourseNumber'])]
final class CourseTemplate implements \JsonSerializable
{
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


    #[ORM\Column(name: 'ImpactAreaCode', type: Types::STRING, length: 1)]
    private string $ImpactAreaCode = "";
    public function getImpactAreaCode(): string
    {
        return $this->ImpactAreaCode;
    }
    public function setImpactAreaCode(string $ImpactAreaCode): static
    {
        $this->ImpactAreaCode = $ImpactAreaCode;
        return $this;
    }


    #[ORM\Column(name: "SubjectCode", type: Types::STRING, length: 4)]
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

    #endregion Column Definitions


    #region Values Methods

    /**
     * @return array<string,mixed>
     */
    public function getValues(): array
    {
        return [
            'id'             => $this->getId(),
            'ImpactAreaCode' => $this->getImpactAreaCode(),
            'SubjectCode'    => $this->getSubjectCode(),
            'CourseNumber'   => $this->getCourseNumber()
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
        if ($values instanceof self) {
            $values = $values->getValues();
        }

        $v = new EntityValidators(self::class, $values);
        return $this
            ->setImpactAreaCode($v->string('ImpactAreaCode'))
            ->setSubjectCode($v->string('SubjectCode'))
            ->setCourseNumber($v->string('CourseNumber'));
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
        return (
            $a->getImpactAreaCode() === $b->getImpactAreaCode() &&
            $a->getSubjectCode() === $b->getSubjectCode() &&
            $a->getCourseNumber() === $b->getCourseNumber()
        );
    }

    #endregion Values Methods


    public function jsonSerialize(): mixed
    {
        return $this->getValues();
    }


    /**
     * @param array<string,mixed>|self|null $values
     */
    public function __construct(array|self|null $values = null)
    {
        if ($values !== null) {
            $this->setValues($values);
        }
    }
}
