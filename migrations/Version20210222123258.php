<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210222123258 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE endereco (id VARCHAR(255) NOT NULL, aluno_id VARCHAR(255) DEFAULT NULL, created_by_id VARCHAR(255) DEFAULT NULL, last_updated_by VARCHAR(255) DEFAULT NULL, cep VARCHAR(9) NOT NULL, street VARCHAR(200) NOT NULL, number INT NOT NULL, complement VARCHAR(100) DEFAULT NULL, bairro VARCHAR(100) NOT NULL, cidade VARCHAR(100) NOT NULL, cod_cidade_ibge INT DEFAULT NULL, estado VARCHAR(2) NOT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_F8E0D60EB2DDF7F4 (aluno_id), INDEX IDX_F8E0D60EB03A8386 (created_by_id), INDEX IDX_F8E0D60EFF8A180B (last_updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE endereco ADD CONSTRAINT FK_F8E0D60EB2DDF7F4 FOREIGN KEY (aluno_id) REFERENCES aluno (id)');
        $this->addSql('ALTER TABLE endereco ADD CONSTRAINT FK_F8E0D60EB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE endereco ADD CONSTRAINT FK_F8E0D60EFF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE endereco');
    }
}
