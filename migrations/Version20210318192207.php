<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210318192207 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE horario_data (id VARCHAR(255) NOT NULL, turma_id VARCHAR(255) DEFAULT NULL, horario_id VARCHAR(255) DEFAULT NULL, created_by_id VARCHAR(255) DEFAULT NULL, last_updated_by VARCHAR(255) DEFAULT NULL, data_aula DATETIME DEFAULT NULL, hora_inicio TIME NOT NULL, hora_termino TIME NOT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_100E2814CEBA2CFD (turma_id), INDEX IDX_100E28144959F1BA (horario_id), INDEX IDX_100E2814B03A8386 (created_by_id), INDEX IDX_100E2814FF8A180B (last_updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE horario_data ADD CONSTRAINT FK_100E2814CEBA2CFD FOREIGN KEY (turma_id) REFERENCES turma (id)');
        $this->addSql('ALTER TABLE horario_data ADD CONSTRAINT FK_100E28144959F1BA FOREIGN KEY (horario_id) REFERENCES horario (id)');
        $this->addSql('ALTER TABLE horario_data ADD CONSTRAINT FK_100E2814B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE horario_data ADD CONSTRAINT FK_100E2814FF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE turma ADD quantidade_aulas INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE horario_data');
        $this->addSql('ALTER TABLE turma DROP quantidade_aulas');
    }
}
