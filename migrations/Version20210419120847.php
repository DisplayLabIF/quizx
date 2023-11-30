<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210419120847 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE questao_opcoes (id VARCHAR(255) NOT NULL, questao_id VARCHAR(255) DEFAULT NULL, created_by_id VARCHAR(255) DEFAULT NULL, last_updated_by VARCHAR(255) DEFAULT NULL, texto LONGTEXT DEFAULT NULL, resposta_correta TINYINT(1) DEFAULT NULL, imagem LONGTEXT DEFAULT NULL, numero_opcao INT NOT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_5336611CB1A8E7E (questao_id), INDEX IDX_5336611B03A8386 (created_by_id), INDEX IDX_5336611FF8A180B (last_updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE questao_opcoes ADD CONSTRAINT FK_5336611CB1A8E7E FOREIGN KEY (questao_id) REFERENCES questoes (id)');
        $this->addSql('ALTER TABLE questao_opcoes ADD CONSTRAINT FK_5336611B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE questao_opcoes ADD CONSTRAINT FK_5336611FF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE questao_opcoes');
    }
}
