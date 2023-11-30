<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210311131148 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE aula ADD modulo_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE aula ADD CONSTRAINT FK_31990A4C07F55F5 FOREIGN KEY (modulo_id) REFERENCES modulo (id)');
        $this->addSql('CREATE INDEX IDX_31990A4C07F55F5 ON aula (modulo_id)');
        $this->addSql('ALTER TABLE aula_presenca DROP FOREIGN KEY FK_A2C08A7EC07F55F5');
        $this->addSql('DROP INDEX IDX_A2C08A7EC07F55F5 ON aula_presenca');
        $this->addSql('ALTER TABLE aula_presenca DROP modulo_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE aula DROP FOREIGN KEY FK_31990A4C07F55F5');
        $this->addSql('DROP INDEX IDX_31990A4C07F55F5 ON aula');
        $this->addSql('ALTER TABLE aula DROP modulo_id');
        $this->addSql('ALTER TABLE aula_presenca ADD modulo_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE aula_presenca ADD CONSTRAINT FK_A2C08A7EC07F55F5 FOREIGN KEY (modulo_id) REFERENCES modulo (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_A2C08A7EC07F55F5 ON aula_presenca (modulo_id)');
    }
}
