<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220717164751 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE salary_department CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:department_id)\'');
        $this->addSql('ALTER TABLE salary_employee CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:employee_id)\', CHANGE department_id department_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:department_id)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE salary_department CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE salary_employee CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE department_id department_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
    }
}
