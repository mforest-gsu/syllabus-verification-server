<?php

declare(strict_types=1);

namespace Gsu\SyllabusVerification\Query;

final class GetCourseSectionsQuery implements \Stringable
{
    public function __construct(private string $termCode)
    {
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return "
            SELECT
                STVTERM_CODE          AS \"TermCode\",
                STVTERM_DESC          AS \"TermDescription\",
                STVCOLL_CODE          AS \"CollegeCode\",
                STVCOLL_DESC          AS \"CollegeDescription\",
                STVDEPT_CODE          AS \"DepartmentCode\",
                STVDEPT_DESC          AS \"DepartmentDescription\",
                SSBSECT_CRN           AS \"CRN\",
                SSBSECT_SUBJ_CODE     AS \"SubjectCode\",
                SSBSECT_CRSE_NUMB     AS \"CourseNumber\",
                SSBSECT_SEQ_NUMB      AS \"SequenceNumber\",
                SCBCRSE_TITLE         AS \"CourseTitle\",
                SIRASGN_PIDM          AS \"INSTR_PIDM\",
                SPRIDEN_FIRST_NAME    AS \"InstructorFirstName\",
                SPRIDEN_LAST_NAME     AS \"InstructorLastName\",
                GOREMAL_EMAIL_ADDRESS AS \"InstructorEmail\"
            FROM
                STVTERM@BIPROD_BREPT_LINK_MFOREST,
                SSBSECT@BIPROD_BREPT_LINK_MFOREST,
                SCBCRSE@BIPROD_BREPT_LINK_MFOREST,
                SSBOVRR@BIPROD_BREPT_LINK_MFOREST,
                SIRASGN@BIPROD_BREPT_LINK_MFOREST,
                SPRIDEN@BIPROD_BREPT_LINK_MFOREST,
                GOREMAL@BIPROD_BREPT_LINK_MFOREST,
                STVCOLL@BIPROD_BREPT_LINK_MFOREST,
                STVDEPT@BIPROD_BREPT_LINK_MFOREST
            WHERE
                -- STVTERM
                STVTERM_CODE = '{$this->termCode}'

                -- SSBSECT
                AND SSBSECT_TERM_CODE = STVTERM_CODE 
                AND SSBSECT_SSTS_CODE = 'A'
                AND SSBSECT_INTG_CDE IS NULL

                -- SCBCRSE
                AND SCBCRSE_SUBJ_CODE = SSBSECT_SUBJ_CODE
                AND SCBCRSE_CRSE_NUMB = SSBSECT_CRSE_NUMB
                AND SCBCRSE_EFF_TERM = (
                    SELECT
                        MAX(i.SCBCRSE_EFF_TERM)
                    FROM
                        SCBCRSE@BIPROD_BREPT_LINK_MFOREST i
                    WHERE
                        i.SCBCRSE_SUBJ_CODE = SSBSECT_SUBJ_CODE AND
                        i.SCBCRSE_CRSE_NUMB = SSBSECT_CRSE_NUMB AND
                        i.SCBCRSE_EFF_TERM <= SSBSECT_TERM_CODE
                )
                AND SCBCRSE_CSTA_CODE = 'A'

                -- SFRSTCR
                AND EXISTS (
                    SELECT
                        1
                    FROM
                        SFRSTCR@BIPROD_BREPT_LINK_MFOREST
                    WHERE
                        SFRSTCR_TERM_CODE = SSBSECT_TERM_CODE AND
                        SFRSTCR_CRN = SSBSECT_CRN AND
                        SFRSTCR_RSTS_CODE IN ('RE','RC','RW','LT','RA','RO')
                )

                -- SSBOVRR
                AND SSBOVRR_TERM_CODE(+) = SSBSECT_TERM_CODE
                AND SSBOVRR_CRN(+) = SSBSECT_CRN

                -- SIRASGN
                AND SIRASGN_TERM_CODE(+) = SSBSECT_TERM_CODE
                AND SIRASGN_CRN(+) = SSBSECT_CRN
                AND SIRASGN_PRIMARY_IND(+) = 'Y'

                -- SPRIDEN
                AND SPRIDEN_PIDM(+) = SIRASGN_PIDM
                AND SPRIDEN_CHANGE_IND(+) IS NULL 

                -- GOREMAL
                AND GOREMAL_PIDM(+) = SIRASGN_PIDM
                AND GOREMAL_EMAL_CODE(+) = 'FSEM'

                -- STVCOLL
                AND STVCOLL_CODE(+) = NVL(SSBOVRR_COLL_CODE, SCBCRSE_COLL_CODE)

                -- STVDEPT
                AND STVDEPT_CODE(+) = NVL(SSBOVRR_DEPT_CODE, SCBCRSE_DEPT_CODE)
        ";
    }
}
