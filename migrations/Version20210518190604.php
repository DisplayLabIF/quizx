<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210518190604 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE resposta_quiz (id VARCHAR(255) NOT NULL, quiz_id VARCHAR(255) DEFAULT NULL, created_by_id VARCHAR(255) DEFAULT NULL, last_updated_by VARCHAR(255) DEFAULT NULL, aluno VARCHAR(255) NOT NULL, nota NUMERIC(6, 2) NOT NULL, finalizou TINYINT(1) DEFAULT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_B13BA86A853CD175 (quiz_id), INDEX IDX_B13BA86AB03A8386 (created_by_id), INDEX IDX_B13BA86AFF8A180B (last_updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resposta_quiz_questoes (id VARCHAR(255) NOT NULL, resposta_quiz_id VARCHAR(255) DEFAULT NULL, questao_id VARCHAR(255) DEFAULT NULL, created_by_id VARCHAR(255) DEFAULT NULL, last_updated_by VARCHAR(255) DEFAULT NULL, correto TINYINT(1) DEFAULT NULL, resposta LONGTEXT DEFAULT NULL, pulou TINYINT(1) DEFAULT NULL, qtd_tentativas INT DEFAULT 1 NOT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_CE4432B4F4D454B2 (resposta_quiz_id), INDEX IDX_CE4432B4CB1A8E7E (questao_id), INDEX IDX_CE4432B4B03A8386 (created_by_id), INDEX IDX_CE4432B4FF8A180B (last_updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE resposta_quiz ADD CONSTRAINT FK_B13BA86A853CD175 FOREIGN KEY (quiz_id) REFERENCES quizes (id)');
        $this->addSql('ALTER TABLE resposta_quiz ADD CONSTRAINT FK_B13BA86AB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE resposta_quiz ADD CONSTRAINT FK_B13BA86AFF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE resposta_quiz_questoes ADD CONSTRAINT FK_CE4432B4F4D454B2 FOREIGN KEY (resposta_quiz_id) REFERENCES resposta_quiz (id)');
        $this->addSql('ALTER TABLE resposta_quiz_questoes ADD CONSTRAINT FK_CE4432B4CB1A8E7E FOREIGN KEY (questao_id) REFERENCES questoes (id)');
        $this->addSql('ALTER TABLE resposta_quiz_questoes ADD CONSTRAINT FK_CE4432B4B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE resposta_quiz_questoes ADD CONSTRAINT FK_CE4432B4FF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resposta_quiz_questoes DROP FOREIGN KEY FK_CE4432B4F4D454B2');
        $this->addSql('DROP TABLE resposta_quiz');
        $this->addSql('DROP TABLE resposta_quiz_questoes');
    }
}
