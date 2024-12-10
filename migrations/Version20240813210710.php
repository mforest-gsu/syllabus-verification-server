<?php

declare(strict_types=1);

namespace Migrations\Gsu\SyllabusVerification;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240813210710 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE CoreImpactsCourseSection
                CHANGE TermDescription TermDescription VARCHAR(64) NOT NULL,
                CHANGE CollegeDescription CollegeDescription VARCHAR(64) NOT NULL,
                CHANGE DepartmentDescription DepartmentDescription VARCHAR(64) NOT NULL,
                CHANGE CourseTitle CourseTitle VARCHAR(64) NOT NULL
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE CoreImpactsCourseSection
                CHANGE TermDescription TermDescription VARCHAR(30) NOT NULL,
                CHANGE CollegeDescription CollegeDescription VARCHAR(30) NOT NULL,
                CHANGE DepartmentDescription DepartmentDescription VARCHAR(30) NOT NULL,
                CHANGE CourseTitle CourseTitle VARCHAR(30) NOT NULL
        ');
    }
}
