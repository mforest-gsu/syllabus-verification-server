<?php

declare(strict_types=1);

namespace Gsu\SyllabusVerification\Entity;

final class SelectedCourseSections
{
    /**
     * @param int[] $selected
     */
    public function __construct(public array $selected = [])
    {
    }
}
