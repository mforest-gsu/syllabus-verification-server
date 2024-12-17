<?php

declare(strict_types=1);

namespace Gsu\SyllabusVerification\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gsu\SyllabusVerification\Repository\CourseSectionLogRepository;

#[ORM\Entity(CourseSectionLogRepository::class)]
#[ORM\Table('CoreImpactsCourseSectionLog')]
#[ORM\UniqueConstraint('udxCoreImpactsCourseSectionLog_01', ['TermCode', 'CRN', 'LogUser', 'LogTimestamp'])]
class CourseSectionLog implements \JsonSerializable
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


    #[ORM\Column(name: 'LogTimestamp', type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $LogTimestamp;
    public function getLogTimestamp(): \DateTimeInterface
    {
        return isset($this->LogTimestamp) ? $this->LogTimestamp : new \DateTime();
    }
    public function setLogTimestamp(\DateTimeInterface $LogTimestamp): static
    {
        $this->LogTimestamp = $LogTimestamp;
        return $this;
    }


    #[ORM\Column(name: 'LogUser', type: Types::STRING, length: 30)]
    private string|null $LogUser = "";
    public function getLogUser(): string|null
    {
        return $this->LogUser;
    }
    public function setLogUser(string|null $LogUser): static
    {
        $this->LogUser = $LogUser;
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


    #endregion


    #region Values Methods

    /**
     * @return array<string,mixed>
     */
    public function getValues(): array
    {
        return [
            'id'             => $this->getId(),
            'TermCode'       => $this->getTermCode(),
            'CRN'            => $this->getCRN(),
            'LogTimestamp'   => $this->getLogTimestamp(),
            'LogUser'        => $this->getLogUser(),
            'VerifyStatus'   => $this->getVerifyStatus()
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
            ->setTermCode($v->string('TermCode'))
            ->setCRN($v->string('CRN'))
            ->setLogTimestamp($v->obj(
                'LogTimestamp',
                \DateTimeInterface::class,
                fn (mixed $v): \DateTimeInterface|null => is_scalar($v)
                    ? new \DateTime(strval($v))
                    : null
            ))
            ->setLogUser($v->string('LogUser'))
            ->setVerifyStatus($v->string('VerifyStatus'));
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
            $a->getTermCode() === $b->getTermCode() &&
            $a->getCRN() === $b->getCRN() &&
            $a->getLogTimestamp()->getTimestamp() === $b->getLogTimestamp()->getTimestamp() &&
            $a->getLogUser() === $b->getLogUser() &&
            $a->getVerifyStatus() === $b->getVerifyStatus()
        );
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
            $this->setValues($values);
        }
    }
}
