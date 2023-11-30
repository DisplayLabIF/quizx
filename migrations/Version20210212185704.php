<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210212185704 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE escola (id VARCHAR(255) NOT NULL, created_by_id VARCHAR(255) DEFAULT NULL, last_updated_by VARCHAR(255) DEFAULT NULL, nome VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, cnpj VARCHAR(255) NOT NULL, cursos_disponiveis_venda JSON NOT NULL, email VARCHAR(255) NOT NULL, descricao LONGTEXT DEFAULT NULL, banco VARCHAR(255) NOT NULL, agencia VARCHAR(255) NOT NULL, conta VARCHAR(255) NOT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_CE6C04C0B03A8386 (created_by_id), INDEX IDX_CE6C04C0FF8A180B (last_updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE escola ADD CONSTRAINT FK_CE6C04C0B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE escola ADD CONSTRAINT FK_CE6C04C0FF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE curso ADD created_by_id VARCHAR(255) DEFAULT NULL, ADD last_updated_by VARCHAR(255) DEFAULT NULL, ADD created DATETIME DEFAULT NULL, ADD updated DATETIME DEFAULT NULL, ADD deleted_at DATETIME DEFAULT NULL, ADD active TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE curso ADD CONSTRAINT FK_CA3B40ECB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE curso ADD CONSTRAINT FK_CA3B40ECFF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_CA3B40ECB03A8386 ON curso (created_by_id)');
        $this->addSql('CREATE INDEX IDX_CA3B40ECFF8A180B ON curso (last_updated_by)');
        $this->addSql('ALTER TABLE turma ADD created_by_id VARCHAR(255) DEFAULT NULL, ADD last_updated_by VARCHAR(255) DEFAULT NULL, ADD created DATETIME DEFAULT NULL, ADD updated DATETIME DEFAULT NULL, ADD deleted_at DATETIME DEFAULT NULL, ADD active TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE turma ADD CONSTRAINT FK_2B0219A6B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE turma ADD CONSTRAINT FK_2B0219A6FF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2B0219A6B03A8386 ON turma (created_by_id)');
        $this->addSql('CREATE INDEX IDX_2B0219A6FF8A180B ON turma (last_updated_by)');
        $this->addSql('ALTER TABLE user ADD escola_id VARCHAR(255) DEFAULT NULL, ADD active TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D786BBC9 FOREIGN KEY (escola_id) REFERENCES escola (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649D786BBC9 ON user (escola_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D786BBC9');
        $this->addSql('DROP TABLE escola');
        $this->addSql('ALTER TABLE curso DROP FOREIGN KEY FK_CA3B40ECB03A8386');
        $this->addSql('ALTER TABLE curso DROP FOREIGN KEY FK_CA3B40ECFF8A180B');
        $this->addSql('DROP INDEX IDX_CA3B40ECB03A8386 ON curso');
        $this->addSql('DROP INDEX IDX_CA3B40ECFF8A180B ON curso');
        $this->addSql('ALTER TABLE curso DROP created_by_id, DROP last_updated_by, DROP created, DROP updated, DROP deleted_at, DROP active');
        $this->addSql('ALTER TABLE turma DROP FOREIGN KEY FK_2B0219A6B03A8386');
        $this->addSql('ALTER TABLE turma DROP FOREIGN KEY FK_2B0219A6FF8A180B');
        $this->addSql('DROP INDEX IDX_2B0219A6B03A8386 ON turma');
        $this->addSql('DROP INDEX IDX_2B0219A6FF8A180B ON turma');
        $this->addSql('ALTER TABLE turma DROP created_by_id, DROP last_updated_by, DROP created, DROP updated, DROP deleted_at, DROP active');
        $this->addSql('DROP INDEX IDX_8D93D649D786BBC9 ON user');
        $this->addSql('ALTER TABLE user DROP escola_id, DROP active');
    }
}
