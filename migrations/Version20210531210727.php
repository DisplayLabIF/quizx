<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210531210727 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE arquivos_questao (id VARCHAR(255) NOT NULL, created_by_id VARCHAR(255) DEFAULT NULL, last_updated_by VARCHAR(255) DEFAULT NULL, url_arquivo_resposta LONGTEXT DEFAULT NULL, tipo_arquivo_resposta VARCHAR(255) DEFAULT NULL, provider_resposta VARCHAR(255) DEFAULT NULL, url_arquivo_explicacao_resposta LONGTEXT DEFAULT NULL, tipo_arquivo_explicacao_resposta VARCHAR(255) DEFAULT NULL, provider_explicacao_resposta VARCHAR(255) DEFAULT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_8E07875B03A8386 (created_by_id), INDEX IDX_8E07875FF8A180B (last_updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE arquivos_questao ADD CONSTRAINT FK_8E07875B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE arquivos_questao ADD CONSTRAINT FK_8E07875FF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE questoes ADD arquivos_questao_id VARCHAR(255) DEFAULT NULL, DROP url_arquivo, DROP tipo_arquivo, DROP provider');
        $this->addSql('ALTER TABLE questoes ADD CONSTRAINT FK_2B93B1AF73977E05 FOREIGN KEY (arquivos_questao_id) REFERENCES arquivos_questao (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2B93B1AF73977E05 ON questoes (arquivos_questao_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE questoes DROP FOREIGN KEY FK_2B93B1AF73977E05');
        $this->addSql('DROP TABLE arquivos_questao');
        $this->addSql('DROP INDEX UNIQ_2B93B1AF73977E05 ON questoes');
        $this->addSql('ALTER TABLE questoes ADD url_arquivo LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD provider VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE arquivos_questao_id tipo_arquivo VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
