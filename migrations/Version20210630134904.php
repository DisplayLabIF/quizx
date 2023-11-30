<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210630134904 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE nivelamento_configuracao (id VARCHAR(255) NOT NULL, created_by_id VARCHAR(255) DEFAULT NULL, last_updated_by VARCHAR(255) DEFAULT NULL, obter_dados_respondente TINYINT(1) NOT NULL, obter_nome TINYINT(1) NOT NULL, obter_email TINYINT(1) NOT NULL, obter_telefone TINYINT(1) NOT NULL, obter_empresa TINYINT(1) NOT NULL, obter_cpf TINYINT(1) NOT NULL, obter_cnpj TINYINT(1) NOT NULL, obter_cidade TINYINT(1) NOT NULL, quando_obter_dados VARCHAR(255) DEFAULT NULL, mostrar_nota VARCHAR(255) NOT NULL, definir_tempo_resposta TINYINT(1) NOT NULL, tempo INT DEFAULT NULL, pode_pular_questao TINYINT(1) NOT NULL, mostra_aleatoria TINYINT(1) DEFAULT NULL, mostrar_correcao TINYINT(1) NOT NULL, configurar_land_page TINYINT(1) NOT NULL, mostrar_gabarito TINYINT(1) NOT NULL, redirecionar_usuario TINYINT(1) NOT NULL, url_call_back LONGTEXT DEFAULT NULL, observacao LONGTEXT DEFAULT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_DFB11BCB03A8386 (created_by_id), INDEX IDX_DFB11BCFF8A180B (last_updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE nivelamento_configuracao ADD CONSTRAINT FK_DFB11BCB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE nivelamento_configuracao ADD CONSTRAINT FK_DFB11BCFF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE nivelamento ADD configuracao_nivelamento_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE nivelamento ADD CONSTRAINT FK_69965F5A94EEA23D FOREIGN KEY (configuracao_nivelamento_id) REFERENCES nivelamento_configuracao (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_69965F5A94EEA23D ON nivelamento (configuracao_nivelamento_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nivelamento DROP FOREIGN KEY FK_69965F5A94EEA23D');
        $this->addSql('DROP TABLE nivelamento_configuracao');
        $this->addSql('DROP INDEX UNIQ_69965F5A94EEA23D ON nivelamento');
        $this->addSql('ALTER TABLE nivelamento DROP configuracao_nivelamento_id');
    }
}
