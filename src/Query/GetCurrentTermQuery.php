<?php

declare(strict_types=1);

namespace Gsu\SyllabusVerification\Query;

final class GetCurrentTermQuery implements \Stringable
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return "
            SELECT
                MIN(i.STVTERM_CODE) AS STVTERM_CODE
            FROM
                STVTERM@BIPROD_BREPT_LINK_MFOREST i
            WHERE
                SYSDATE < i.STVTERM_END_DATE + 1
        ";
    }
}
