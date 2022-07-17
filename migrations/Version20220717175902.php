<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220717175902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE employment_department (id CHAR(36) NOT NULL COMMENT \'(DC2Type:department_id)\', name VARCHAR(255) NOT NULL, INDEX IDX_A21B0F97BF396750 (id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employment_employee (id CHAR(36) NOT NULL COMMENT \'(DC2Type:employee_id)\', department_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:department_id)\', first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_A24E9E9EAE80F5DF (department_id), INDEX IDX_A24E9E9EBF396750 (id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE employment_employee ADD CONSTRAINT FK_A24E9E9EAE80F5DF FOREIGN KEY (department_id) REFERENCES employment_department (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employment_employee DROP FOREIGN KEY FK_A24E9E9EAE80F5DF');
        $this->addSql('DROP TABLE employment_department');
        $this->addSql('DROP TABLE employment_employee');
    }
}
