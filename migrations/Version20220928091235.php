<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220928091235 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE leads_nivelamento DROP FOREIGN KEY FK_497E3BCB232519D5');
        $this->addSql('ALTER TABLE quizes_nivelamento DROP FOREIGN KEY FK_17EC313D232519D5');
        $this->addSql('ALTER TABLE resposta_quiz DROP FOREIGN KEY FK_B13BA86A232519D5');
        $this->addSql('ALTER TABLE campos_personalizados_configuracao_nivelamento DROP FOREIGN KEY FK_8F9697C494EEA23D');
        $this->addSql('ALTER TABLE nivelamento DROP FOREIGN KEY FK_69965F5A94EEA23D');
        $this->addSql('DROP TABLE campos_personalizados_configuracao_nivelamento');
        $this->addSql('DROP TABLE leads_nivelamento');
        $this->addSql('DROP TABLE nivelamento');
        $this->addSql('DROP TABLE nivelamento_configuracao');
        $this->addSql('DROP TABLE quizes_nivelamento');
        $this->addSql('DROP INDEX IDX_B13BA86A232519D5 ON resposta_quiz');
        $this->addSql('ALTER TABLE resposta_quiz DROP nivelamento_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE campos_personalizados_configuracao_nivelamento (configuracao_nivelamento_id VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, campo_personalizado_id VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_8F9697C494EEA23D (configuracao_nivelamento_id), INDEX IDX_8F9697C4FFDC0C6B (campo_personalizado_id), PRIMARY KEY(configuracao_nivelamento_id, campo_personalizado_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE leads_nivelamento (lead_id VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, nivelamento_id VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_497E3BCB232519D5 (nivelamento_id), INDEX IDX_497E3BCB55458D (lead_id), PRIMARY KEY(lead_id, nivelamento_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE nivelamento (id VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, user_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created_by_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, last_updated_by VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, configuracao_nivelamento_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, nome VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT 1 NOT NULL, codigo VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_69965F5AA76ED395 (user_id), INDEX IDX_69965F5AB03A8386 (created_by_id), INDEX IDX_69965F5AFF8A180B (last_updated_by), UNIQUE INDEX UNIQ_69965F5A94EEA23D (configuracao_nivelamento_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE nivelamento_configuracao (id VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_by_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, last_updated_by VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, obter_dados_respondente TINYINT(1) NOT NULL, obter_nome TINYINT(1) NOT NULL, obter_email TINYINT(1) NOT NULL, obter_telefone TINYINT(1) NOT NULL, obter_empresa TINYINT(1) NOT NULL, obter_cpf TINYINT(1) NOT NULL, obter_cnpj TINYINT(1) NOT NULL, obter_cidade TINYINT(1) NOT NULL, quando_obter_dados VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, mostrar_nota VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, definir_tempo_resposta TINYINT(1) NOT NULL, tempo INT DEFAULT NULL, pode_pular_questao TINYINT(1) NOT NULL, mostra_aleatoria TINYINT(1) DEFAULT NULL, mostrar_correcao TINYINT(1) NOT NULL, configurar_land_page TINYINT(1) DEFAULT NULL, mostrar_gabarito TINYINT(1) NOT NULL, redirecionar_usuario TINYINT(1) NOT NULL, url_call_back LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, observacao LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT 1 NOT NULL, ordem_campos JSON DEFAULT NULL, redirecionar_automaticamente TINYINT(1) DEFAULT 1 NOT NULL, nome_botao_redirecionar VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_DFB11BCB03A8386 (created_by_id), INDEX IDX_DFB11BCFF8A180B (last_updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE quizes_nivelamento (quiz_id VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, nivelamento_id VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ordem INT NOT NULL, pontuacao_minima DOUBLE PRECISION DEFAULT NULL, INDEX IDX_17EC313D232519D5 (nivelamento_id), INDEX IDX_17EC313D853CD175 (quiz_id), PRIMARY KEY(quiz_id, nivelamento_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE campos_personalizados_configuracao_nivelamento ADD CONSTRAINT FK_8F9697C494EEA23D FOREIGN KEY (configuracao_nivelamento_id) REFERENCES nivelamento_configuracao (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE campos_personalizados_configuracao_nivelamento ADD CONSTRAINT FK_8F9697C4FFDC0C6B FOREIGN KEY (campo_personalizado_id) REFERENCES campos_personalizados (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE leads_nivelamento ADD CONSTRAINT FK_497E3BCB232519D5 FOREIGN KEY (nivelamento_id) REFERENCES nivelamento (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE leads_nivelamento ADD CONSTRAINT FK_497E3BCB55458D FOREIGN KEY (lead_id) REFERENCES quiz_lead (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE nivelamento ADD CONSTRAINT FK_69965F5A94EEA23D FOREIGN KEY (configuracao_nivelamento_id) REFERENCES nivelamento_configuracao (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE nivelamento ADD CONSTRAINT FK_69965F5AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE nivelamento ADD CONSTRAINT FK_69965F5AB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE nivelamento ADD CONSTRAINT FK_69965F5AFF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE nivelamento_configuracao ADD CONSTRAINT FK_DFB11BCB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE nivelamento_configuracao ADD CONSTRAINT FK_DFB11BCFF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE quizes_nivelamento ADD CONSTRAINT FK_17EC313D232519D5 FOREIGN KEY (nivelamento_id) REFERENCES nivelamento (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE quizes_nivelamento ADD CONSTRAINT FK_17EC313D853CD175 FOREIGN KEY (quiz_id) REFERENCES quizes (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE resposta_quiz ADD nivelamento_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE resposta_quiz ADD CONSTRAINT FK_B13BA86A232519D5 FOREIGN KEY (nivelamento_id) REFERENCES nivelamento (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_B13BA86A232519D5 ON resposta_quiz (nivelamento_id)');
    }
}
