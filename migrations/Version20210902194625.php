<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210902194625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE aula ADD quiz_id VARCHAR(255) DEFAULT NULL, ADD file LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE aula ADD CONSTRAINT FK_31990A4853CD175 FOREIGN KEY (quiz_id) REFERENCES quizes (id)');
        $this->addSql('CREATE INDEX IDX_31990A4853CD175 ON aula (quiz_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE aula DROP FOREIGN KEY FK_31990A4853CD175');
        $this->addSql('DROP INDEX IDX_31990A4853CD175 ON aula');
        $this->addSql('ALTER TABLE aula DROP quiz_id, DROP file');
    }
}
