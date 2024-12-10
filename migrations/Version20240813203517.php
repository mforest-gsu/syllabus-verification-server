<?php

declare(strict_types=1);

namespace Migrations\Gsu\SyllabusVerification;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240813203517 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE CoreImpactLabelOverride (
                id INT AUTO_INCREMENT NOT NULL,
                LabelCategory VARCHAR(16) NOT NULL,
                LabelCode VARCHAR(16) NOT NULL,
                LabelValue VARCHAR(64) NOT NULL,
                UNIQUE INDEX udxCoreImpactLabelOverride_01 (LabelCategory, LabelCode),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql("
            INSERT IGNORE INTO CoreImpactLabelOverride
                (LabelCategory, LabelCode, LabelValue)
            VALUES
                ('STVCOLL', 'NS', 'Coll. of Nursing & Health Prof.')
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE CoreImpactLabelOverride');
    }
}
