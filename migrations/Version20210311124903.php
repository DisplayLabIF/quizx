<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210311124903 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE horario (id VARCHAR(255) NOT NULL, created_by_id VARCHAR(255) DEFAULT NULL, last_updated_by VARCHAR(255) DEFAULT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_E25853A3B03A8386 (created_by_id), INDEX IDX_E25853A3FF8A180B (last_updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE horario ADD CONSTRAINT FK_E25853A3B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE horario ADD CONSTRAINT FK_E25853A3FF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE adm_escola ADD escola_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE adm_escola ADD CONSTRAINT FK_3DFD5EC7D786BBC9 FOREIGN KEY (escola_id) REFERENCES escola (id)');
        $this->addSql('CREATE INDEX IDX_3DFD5EC7D786BBC9 ON adm_escola (escola_id)');
        $this->addSql('ALTER TABLE turma ADD Horario_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE turma ADD CONSTRAINT FK_2B0219A6604F26A FOREIGN KEY (Horario_id) REFERENCES horario (id)');
        $this->addSql('CREATE INDEX IDX_2B0219A6604F26A ON turma (Horario_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D786BBC9');
        $this->addSql('DROP INDEX IDX_8D93D649D786BBC9 ON user');
        $this->addSql('ALTER TABLE user DROP escola_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE turma DROP FOREIGN KEY FK_2B0219A6604F26A');
        $this->addSql('DROP TABLE horario');
        $this->addSql('ALTER TABLE adm_escola DROP FOREIGN KEY FK_3DFD5EC7D786BBC9');
        $this->addSql('DROP INDEX IDX_3DFD5EC7D786BBC9 ON adm_escola');
        $this->addSql('ALTER TABLE adm_escola DROP escola_id');
        $this->addSql('DROP INDEX IDX_2B0219A6604F26A ON turma');
        $this->addSql('ALTER TABLE turma DROP Horario_id');
        $this->addSql('ALTER TABLE user ADD escola_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D786BBC9 FOREIGN KEY (escola_id) REFERENCES escola (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8D93D649D786BBC9 ON user (escola_id)');
    }
}
