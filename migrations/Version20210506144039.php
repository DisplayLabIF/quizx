<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210506144039 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE quiz_configiguracao (id VARCHAR(255) NOT NULL, created_by_id VARCHAR(255) DEFAULT NULL, last_updated_by VARCHAR(255) DEFAULT NULL, obter_dados_respondente TINYINT(1) NOT NULL, obter_nome TINYINT(1) NOT NULL, obter_email TINYINT(1) NOT NULL, obter_telefone TINYINT(1) NOT NULL, obter_empresa TINYINT(1) NOT NULL, obter_cpf_ou_cnpj TINYINT(1) NOT NULL, obter_cidade TINYINT(1) NOT NULL, quando_obter_dados VARCHAR(255) NOT NULL, mostrar_nota TINYINT(1) NOT NULL, definir_tempo_resposta TINYINT(1) NOT NULL, pode_pular_questao TINYINT(1) NOT NULL, mostra_aleatoria TINYINT(1) NOT NULL, mostrar_correcao TINYINT(1) NOT NULL, configurar_land_page TINYINT(1) NOT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_3F4440D2B03A8386 (created_by_id), INDEX IDX_3F4440D2FF8A180B (last_updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE quiz_configiguracao ADD CONSTRAINT FK_3F4440D2B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE quiz_configiguracao ADD CONSTRAINT FK_3F4440D2FF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE quizes ADD configuracao_quiz_id VARCHAR(255) DEFAULT NULL, DROP definido_tempo_resposta, DROP tempo_resposta, DROP mostra_todas_questoes, DROP qtd_questoes_por_vez, DROP pode_pular_questao, DROP pode_eliminar_alternativa, DROP qtd_vez_elimina_questao, DROP mostra_aleatoria, DROP somente_emails_lista');
        $this->addSql('ALTER TABLE quizes ADD CONSTRAINT FK_8E40FA135755D334 FOREIGN KEY (configuracao_quiz_id) REFERENCES quiz_configiguracao (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8E40FA135755D334 ON quizes (configuracao_quiz_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quizes DROP FOREIGN KEY FK_8E40FA135755D334');
        $this->addSql('DROP TABLE quiz_configiguracao');
        $this->addSql('DROP INDEX UNIQ_8E40FA135755D334 ON quizes');
        $this->addSql('ALTER TABLE quizes ADD definido_tempo_resposta TINYINT(1) DEFAULT NULL, ADD tempo_resposta TIME DEFAULT NULL, ADD mostra_todas_questoes TINYINT(1) DEFAULT NULL, ADD qtd_questoes_por_vez INT DEFAULT NULL, ADD pode_pular_questao TINYINT(1) DEFAULT NULL, ADD pode_eliminar_alternativa TINYINT(1) DEFAULT NULL, ADD qtd_vez_elimina_questao INT DEFAULT NULL, ADD mostra_aleatoria TINYINT(1) DEFAULT NULL, ADD somente_emails_lista TINYINT(1) DEFAULT \'0\' NOT NULL, DROP configuracao_quiz_id');
    }
}
