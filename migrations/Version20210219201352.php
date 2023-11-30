<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210219201352 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE matricula (id VARCHAR(255) NOT NULL, created_by_id VARCHAR(255) DEFAULT NULL, last_updated_by VARCHAR(255) DEFAULT NULL, data DATETIME NOT NULL, status INT DEFAULT 0 NOT NULL, valor_pago DOUBLE PRECISION DEFAULT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_15DF1885B03A8386 (created_by_id), INDEX IDX_15DF1885FF8A180B (last_updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE turmaMatricula (matricula_id VARCHAR(255) NOT NULL, turma_id VARCHAR(255) NOT NULL, INDEX IDX_9990AF9C15C84B52 (matricula_id), INDEX IDX_9990AF9CCEBA2CFD (turma_id), PRIMARY KEY(matricula_id, turma_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE matricula ADD CONSTRAINT FK_15DF1885B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE matricula ADD CONSTRAINT FK_15DF1885FF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE turmaMatricula ADD CONSTRAINT FK_9990AF9C15C84B52 FOREIGN KEY (matricula_id) REFERENCES matricula (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE turmaMatricula ADD CONSTRAINT FK_9990AF9CCEBA2CFD FOREIGN KEY (turma_id) REFERENCES turma (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD cpf VARCHAR(255) DEFAULT NULL, ADD telefone VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6493E3E11F0 ON user (cpf)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE turmaMatricula DROP FOREIGN KEY FK_9990AF9C15C84B52');
        $this->addSql('DROP TABLE matricula');
        $this->addSql('DROP TABLE turmaMatricula');
        $this->addSql('DROP INDEX UNIQ_8D93D6493E3E11F0 ON user');
        $this->addSql('ALTER TABLE user DROP cpf, DROP telefone');
    }
}
