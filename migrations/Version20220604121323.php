<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220604121323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recursos (id VARCHAR(255) NOT NULL, created_by_id VARCHAR(255) DEFAULT NULL, last_updated_by VARCHAR(255) DEFAULT NULL, nome VARCHAR(50) NOT NULL, role VARCHAR(50) DEFAULT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT 1 NOT NULL, INDEX IDX_5163D17DB03A8386 (created_by_id), INDEX IDX_5163D17DFF8A180B (last_updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recursos ADD CONSTRAINT FK_5163D17DB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE recursos ADD CONSTRAINT FK_5163D17DFF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE escola ADD braspag_merchant_id VARCHAR(255) DEFAULT NULL, DROP cursos_disponiveis_venda');
        $this->addSql('ALTER TABLE quizes CHANGE start_game start_game TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE resposta_quiz ADD CONSTRAINT FK_B13BA86AB2DDF7F4 FOREIGN KEY (aluno_id) REFERENCES aluno (id)');
        $this->addSql('CREATE INDEX IDX_B13BA86AB2DDF7F4 ON resposta_quiz (aluno_id)');
        $this->addSql('ALTER TABLE turma DROP valor, DROP disponivel_matricula, DROP cobrar_matricula, DROP condicao_especial_assinatura');
        $this->addSql('ALTER TABLE user CHANGE password password VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE recursos');
        $this->addSql('ALTER TABLE escola ADD cursos_disponiveis_venda JSON NOT NULL, DROP braspag_merchant_id');
        $this->addSql('ALTER TABLE quizes CHANGE start_game start_game TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE resposta_quiz DROP FOREIGN KEY FK_B13BA86AB2DDF7F4');
        $this->addSql('DROP INDEX IDX_B13BA86AB2DDF7F4 ON resposta_quiz');
        $this->addSql('ALTER TABLE turma ADD valor DOUBLE PRECISION NOT NULL, ADD disponivel_matricula TINYINT(1) NOT NULL, ADD cobrar_matricula TINYINT(1) NOT NULL, ADD condicao_especial_assinatura TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE password password VARCHAR(255) NOT NULL');
    }
}
