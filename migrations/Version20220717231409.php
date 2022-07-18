<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220717231409 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE report_read_model (id CHAR(36) NOT NULL, date DATETIME DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, INDEX REPORT_READ_MODEL_ID_INDEX (id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report_line_read_model (id CHAR(36) NOT NULL, employee_id CHAR(36) DEFAULT NULL, report_id CHAR(36) DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, department VARCHAR(255) DEFAULT NULL, base_salary VARCHAR(255) DEFAULT NULL, bonus VARCHAR(255) DEFAULT NULL, bonus_type VARCHAR(255) DEFAULT NULL, salary VARCHAR(255) DEFAULT NULL, INDEX REPORT_LINE_READ_MODEL_ID_INDEX (id), INDEX REPORT_LINE_READ_MODEL_EMPLOYEE_ID_INDEX (employee_id), INDEX REPORT_LINE_READ_MODEL_REPORT_ID_INDEX (report_id), UNIQUE INDEX UNIQUE_REPORT_LINE_READ_MODEL_REPORT_EMPLOYEE_INDEX (report_id, employee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE report_line_read_model ADD CONSTRAINT FK_REPORT_READ_MODEL FOREIGN KEY (report_id) REFERENCES report_read_model (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE report_line_read_model DROP FOREIGN KEY FK_REPORT_READ_MODEL');
        $this->addSql('DROP TABLE report_read_model');
        $this->addSql('DROP TABLE report_line_read_model');
    }
}
