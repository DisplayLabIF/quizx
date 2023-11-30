<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210222121905 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE aluno (id VARCHAR(255) NOT NULL, contato_id VARCHAR(255) DEFAULT NULL, cpf VARCHAR(14) DEFAULT NULL, rg VARCHAR(14) DEFAULT NULL, data_aniversario DATE DEFAULT NULL, sexo VARCHAR(1) DEFAULT NULL, orgao_expediror VARCHAR(30) DEFAULT NULL, estado_civil VARCHAR(20) DEFAULT NULL, INDEX IDX_67C97100B279BE46 (contato_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contato (id VARCHAR(255) NOT NULL, created_by_id VARCHAR(255) DEFAULT NULL, last_updated_by VARCHAR(255) DEFAULT NULL, telefone VARCHAR(15) DEFAULT NULL, celular VARCHAR(16) DEFAULT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_C384AB42B03A8386 (created_by_id), INDEX IDX_C384AB42FF8A180B (last_updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE aluno ADD CONSTRAINT FK_67C97100B279BE46 FOREIGN KEY (contato_id) REFERENCES contato (id)');
        $this->addSql('ALTER TABLE aluno ADD CONSTRAINT FK_67C97100BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contato ADD CONSTRAINT FK_C384AB42B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE contato ADD CONSTRAINT FK_C384AB42FF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id)');
        $this->addSql('DROP INDEX UNIQ_8D93D6493E3E11F0 ON user');
        $this->addSql('ALTER TABLE user ADD tipo VARCHAR(255) NOT NULL, DROP cpf, DROP telefone');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE aluno DROP FOREIGN KEY FK_67C97100B279BE46');
        $this->addSql('DROP TABLE aluno');
        $this->addSql('DROP TABLE contato');
        $this->addSql('ALTER TABLE user ADD cpf VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD telefone VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP tipo');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6493E3E11F0 ON user (cpf)');
    }
}
