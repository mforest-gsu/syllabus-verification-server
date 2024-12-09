<?php

declare(strict_types=1);

namespace Gsu\SyllabusVerification\Entity;

final class FetchCourseSectionsParameters
{
    /**
     * @param string $TermCode
     * @param string|null $CollegeCode
     * @param string|null $DepartmentCode
     * @param string|null $CRN
     * @param string|null $SubjectCode
     * @param string|null $CourseNumber
     * @param string|null $SequenceNumber
     * @param string|null $CourseTitle
     * @param string|null $InstructorFirstName
     * @param string|null $InstructorLastName
     * @param string|null $InstructorEmail
     * @param string|null $VerifyStatus
     * @param string|null $VerifyDateStart
     * @param string|null $VerifyDateEnd
     * @param string|null $VerifyUser
     */
    public function __construct(
        public string $TermCode = "",
        public string|null $CollegeCode = null,
        public string|null $DepartmentCode = null,
        public string|null $CRN = null,
        public string|null $SubjectCode = null,
        public string|null $CourseNumber = null,
        public string|null $SequenceNumber = null,
        public string|null $CourseTitle = null,
        public string|null $InstructorFirstName = null,
        public string|null $InstructorLastName = null,
        public string|null $InstructorEmail = null,
        public string|null $VerifyStatus = null,
        public string|null $VerifyDateStart = null,
        public string|null $VerifyDateEnd = null,
        public string|null $VerifyUser = null,
    ) {
    }
}
