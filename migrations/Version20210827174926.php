<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210827174926 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE configuracao_marketing_quiz (id VARCHAR(255) NOT NULL, created_by_id VARCHAR(255) DEFAULT NULL, last_updated_by VARCHAR(255) DEFAULT NULL, titulo VARCHAR(255) DEFAULT NULL, image LONGTEXT DEFAULT NULL, script_externos JSON DEFAULT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_91FEA322B03A8386 (created_by_id), INDEX IDX_91FEA322FF8A180B (last_updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE configuracao_marketing_quiz ADD CONSTRAINT FK_91FEA322B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE configuracao_marketing_quiz ADD CONSTRAINT FK_91FEA322FF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE quizes ADD config_marketing_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE quizes ADD CONSTRAINT FK_8E40FA1315D8171B FOREIGN KEY (config_marketing_id) REFERENCES configuracao_marketing_quiz (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8E40FA1315D8171B ON quizes (config_marketing_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quizes DROP FOREIGN KEY FK_8E40FA1315D8171B');
        $this->addSql('DROP TABLE configuracao_marketing_quiz');
        $this->addSql('DROP INDEX UNIQ_8E40FA1315D8171B ON quizes');
        $this->addSql('ALTER TABLE quizes DROP config_marketing_id');
    }
}
