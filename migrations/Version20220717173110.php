<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220717173110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE report_report (id CHAR(36) NOT NULL COMMENT \'(DC2Type:report_id)\', date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status VARCHAR(255) NOT NULL, INDEX IDX_EDDCA21BBF396750 (id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE salary_department (id CHAR(36) NOT NULL COMMENT \'(DC2Type:department_id)\', bonus_rule_bonus_type VARCHAR(255) NOT NULL, bonus_rule_factor INT NOT NULL, INDEX IDX_D96B28B0BF396750 (id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE salary_employee (id CHAR(36) NOT NULL COMMENT \'(DC2Type:employee_id)\', department_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:department_id)\', employment_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_CFDAFFA0AE80F5DF (department_id), INDEX IDX_CFDAFFA0BF396750 (id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE salary_employee ADD CONSTRAINT FK_CFDAFFA0AE80F5DF FOREIGN KEY (department_id) REFERENCES salary_department (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE salary_employee DROP FOREIGN KEY FK_CFDAFFA0AE80F5DF');
        $this->addSql('DROP TABLE report_report');
        $this->addSql('DROP TABLE salary_department');
        $this->addSql('DROP TABLE salary_employee');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
