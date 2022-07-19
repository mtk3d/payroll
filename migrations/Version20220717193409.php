<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220717193409 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE department_read_model (id CHAR(36) NOT NULL, name VARCHAR(255) DEFAULT NULL, bonus_type VARCHAR(255) DEFAULT NULL, bonus_factor VARCHAR(255) DEFAULT NULL, INDEX DEPARTMENT_READ_MODEL_ID_INDEX (id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employee_read_model (id CHAR(36) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, employment_date DATETIME DEFAULT NULL, base_salary VARCHAR(255) DEFAULT NULL, department_id CHAR(36) DEFAULT NULL, INDEX EMPLOYEE_READ_MODEL_ID_INDEX (id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE employee_read_model ADD CONSTRAINT FK_EMPLOYEE_DEPARTMENT_READ_MODEL FOREIGN KEY (department_id) REFERENCES department_read_model (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employee_read_model DROP FOREIGN KEY FK_EMPLOYEE_DEPARTMENT_READ_MODEL');
        $this->addSql('DROP TABLE department_read_model');
        $this->addSql('DROP TABLE employee_read_model');
    }
}
