<?php

declare(strict_types=1);

namespace Gsu\SyllabusVerification\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gsu\SyllabusVerification\Repository\LabelOverrideRepository;

#[ORM\Entity(LabelOverrideRepository::class)]
#[ORM\Table('CoreImpactLabelOverride')]
#[ORM\UniqueConstraint('udxCoreImpactLabelOverride_01', ['LabelCategory', 'LabelCode'])]
class LabelOverride
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


    #[ORM\Column(name: 'LabelCategory', type: Types::STRING, length: 16)]
    private string $LabelCategory = "";
    public function getLabelCategory(): string
    {
        return $this->LabelCategory;
    }
    public function setLabelCategory(string $LabelCategory): static
    {
        $this->LabelCategory = $LabelCategory;
        return $this;
    }


    #[ORM\Column(name: "LabelCode", type: Types::STRING, length: 16)]
    private string $LabelCode = "";
    public function getLabelCode(): string
    {
        return $this->LabelCode;
    }
    public function setLabelCode(string $LabelCode): static
    {
        $this->LabelCode = $LabelCode;
        return $this;
    }


    #[ORM\Column(name: 'LabelValue', type: Types::STRING, length: 64)]
    private string $LabelValue = "";
    public function getLabelValue(): string
    {
        return $this->LabelValue;
    }
    public function setLabelValue(string $LabelValue): static
    {
        $this->LabelValue = $LabelValue;
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
            'id'            => $this->getId(),
            'LabelCategory' => $this->getLabelCategory(),
            'LabelCode'     => $this->getLabelCode(),
            'LabelValue'    => $this->getLabelValue()
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
            ->setLabelCategory($v->string('LabelCategory'))
            ->setLabelCode($v->string('LabelCode'))
            ->setLabelValue($v->string('LabelValue'));
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
            $a->getLabelCategory() === $b->getLabelCategory() &&
            $a->getLabelCode() === $b->getLabelCode() &&
            $a->getLabelValue() === $b->getLabelValue()
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
