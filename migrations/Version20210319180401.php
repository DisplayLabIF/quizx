<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210319180401 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE aula_presenca ADD horario_data_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE aula_presenca ADD CONSTRAINT FK_A2C08A7EC78B4D21 FOREIGN KEY (horario_data_id) REFERENCES horario_data (id)');
        $this->addSql('CREATE INDEX IDX_A2C08A7EC78B4D21 ON aula_presenca (horario_data_id)');
        $this->addSql('ALTER TABLE horario_data ADD chamada TINYINT(1) DEFAULT \'0\' NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE aula_presenca DROP FOREIGN KEY FK_A2C08A7EC78B4D21');
        $this->addSql('DROP INDEX IDX_A2C08A7EC78B4D21 ON aula_presenca');
        $this->addSql('ALTER TABLE aula_presenca DROP horario_data_id');
        $this->addSql('ALTER TABLE horario_data DROP chamada');
    }
}
