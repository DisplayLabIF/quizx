<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210228175248 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE turmaMatricula');
        $this->addSql('ALTER TABLE matricula ADD turma_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE matricula ADD CONSTRAINT FK_15DF1885CEBA2CFD FOREIGN KEY (turma_id) REFERENCES turma (id)');
        $this->addSql('CREATE INDEX IDX_15DF1885CEBA2CFD ON matricula (turma_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE turmaMatricula (matricula_id VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, turma_id VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_9990AF9C15C84B52 (matricula_id), INDEX IDX_9990AF9CCEBA2CFD (turma_id), PRIMARY KEY(matricula_id, turma_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE turmaMatricula ADD CONSTRAINT FK_9990AF9C15C84B52 FOREIGN KEY (matricula_id) REFERENCES matricula (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE turmaMatricula ADD CONSTRAINT FK_9990AF9CCEBA2CFD FOREIGN KEY (turma_id) REFERENCES turma (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE matricula DROP FOREIGN KEY FK_15DF1885CEBA2CFD');
        $this->addSql('DROP INDEX IDX_15DF1885CEBA2CFD ON matricula');
        $this->addSql('ALTER TABLE matricula DROP turma_id');
    }
}
