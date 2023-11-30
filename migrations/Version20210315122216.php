<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210315122216 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE horario ADD turma_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE horario ADD CONSTRAINT FK_E25853A3CEBA2CFD FOREIGN KEY (turma_id) REFERENCES turma (id)');
        $this->addSql('CREATE INDEX IDX_E25853A3CEBA2CFD ON horario (turma_id)');
        $this->addSql('ALTER TABLE turma DROP FOREIGN KEY FK_2B0219A6604F26A');
        $this->addSql('DROP INDEX IDX_2B0219A6604F26A ON turma');
        $this->addSql('ALTER TABLE turma DROP Horario_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE horario DROP FOREIGN KEY FK_E25853A3CEBA2CFD');
        $this->addSql('DROP INDEX IDX_E25853A3CEBA2CFD ON horario');
        $this->addSql('ALTER TABLE horario DROP turma_id');
        $this->addSql('ALTER TABLE turma ADD Horario_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE turma ADD CONSTRAINT FK_2B0219A6604F26A FOREIGN KEY (Horario_id) REFERENCES horario (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_2B0219A6604F26A ON turma (Horario_id)');
    }
}
