<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210315220746 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE horario_aula_turma (id VARCHAR(255) NOT NULL, turma_id VARCHAR(255) DEFAULT NULL, aula_id VARCHAR(255) DEFAULT NULL, created_by_id VARCHAR(255) DEFAULT NULL, last_updated_by VARCHAR(255) DEFAULT NULL, hora_inicio TIME NOT NULL, hora_termino TIME NOT NULL, data_aula DATETIME DEFAULT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_8C6EF796CEBA2CFD (turma_id), INDEX IDX_8C6EF796AD1A1255 (aula_id), INDEX IDX_8C6EF796B03A8386 (created_by_id), INDEX IDX_8C6EF796FF8A180B (last_updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE horario_aula_turma ADD CONSTRAINT FK_8C6EF796CEBA2CFD FOREIGN KEY (turma_id) REFERENCES turma (id)');
        $this->addSql('ALTER TABLE horario_aula_turma ADD CONSTRAINT FK_8C6EF796AD1A1255 FOREIGN KEY (aula_id) REFERENCES aula (id)');
        $this->addSql('ALTER TABLE horario_aula_turma ADD CONSTRAINT FK_8C6EF796B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE horario_aula_turma ADD CONSTRAINT FK_8C6EF796FF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE horario_aula_turma');
    }
}
