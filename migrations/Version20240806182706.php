<?php

declare(strict_types=1);

namespace Migrations\Gsu\SyllabusVerification;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240806182706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE CoreImpactsCourseSectionLog (
                id INT AUTO_INCREMENT NOT NULL,
                TermCode VARCHAR(6) NOT NULL,
                CRN VARCHAR(5) NOT NULL,
                LogTimestamp DATETIME NOT NULL,
                LogUser VARCHAR(30) NOT NULL,
                VerifyStatus VARCHAR(10) NOT NULL,
                UNIQUE INDEX udxCoreImpactsCourseSectionLog_01 (TermCode, CRN, LogUser, LogTimestamp),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE CoreImpactsCourseSectionLog');
    }
}
