<?php

declare(strict_types=1);

namespace Gsu\SyllabusVerification\Entity;

final class FilterValue implements \JsonSerializable
{
    /**
     * @param string $type
     * @param string $value
     * @param string $label
     * @param FilterValue[] $children
     */
    public function __construct(
        public string $type = '',
        public string $value = '',
        public string $label = '',
        public array $children = []
    ) {
    }


    /** @inheritdoc */
    public function jsonSerialize(): mixed
    {
        return array_filter([
            'type' => $this->type,
            'value' => $this->value,
            'label' => $this->label,
            'children' => count($this->children) > 0 ? array_values($this->children) : null
        ], fn($v) => $v !== null && $v !== '');
    }
}
