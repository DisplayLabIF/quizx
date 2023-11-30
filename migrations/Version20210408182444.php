<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210408182444 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE quizes (id VARCHAR(255) NOT NULL, created_by_id VARCHAR(255) DEFAULT NULL, last_updated_by VARCHAR(255) DEFAULT NULL, codigo VARCHAR(255) NOT NULL, nome VARCHAR(50) NOT NULL, definido_tempo_resposta TINYINT(1) DEFAULT NULL, tempo_resposta TIME DEFAULT NULL, mostra_todas_questoes TINYINT(1) DEFAULT NULL, qtd_questoes_por_vez INT DEFAULT NULL, pode_pular_questao TINYINT(1) DEFAULT NULL, pode_eliminar_alternativa TINYINT(1) DEFAULT NULL, qtd_vez_elimina_questao INT DEFAULT NULL, mostra_aleatoria TINYINT(1) DEFAULT NULL, nivel VARCHAR(255) DEFAULT NULL, somente_emails_lista TINYINT(1) DEFAULT \'0\' NOT NULL, url_call_back TEXT DEFAULT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_8E40FA13B03A8386 (created_by_id), INDEX IDX_8E40FA13FF8A180B (last_updated_by), INDEX codigo_idx (codigo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE quizes ADD CONSTRAINT FK_8E40FA13B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE quizes ADD CONSTRAINT FK_8E40FA13FF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE quizes');
    }
}
