<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210629112220 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE notas_quiz (id VARCHAR(255) NOT NULL, quiz_id VARCHAR(255) DEFAULT NULL, resposta_quiz_id VARCHAR(255) DEFAULT NULL, created_by_id VARCHAR(255) DEFAULT NULL, last_updated_by VARCHAR(255) DEFAULT NULL, nota NUMERIC(6, 2) NOT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_1806C800853CD175 (quiz_id), INDEX IDX_1806C800F4D454B2 (resposta_quiz_id), INDEX IDX_1806C800B03A8386 (created_by_id), INDEX IDX_1806C800FF8A180B (last_updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notas_quiz ADD CONSTRAINT FK_1806C800853CD175 FOREIGN KEY (quiz_id) REFERENCES quizes (id)');
        $this->addSql('ALTER TABLE notas_quiz ADD CONSTRAINT FK_1806C800F4D454B2 FOREIGN KEY (resposta_quiz_id) REFERENCES resposta_quiz (id)');
        $this->addSql('ALTER TABLE notas_quiz ADD CONSTRAINT FK_1806C800B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notas_quiz ADD CONSTRAINT FK_1806C800FF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE resposta_quiz ADD nivelamento_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE resposta_quiz ADD CONSTRAINT FK_B13BA86A232519D5 FOREIGN KEY (nivelamento_id) REFERENCES nivelamento (id)');
        $this->addSql('CREATE INDEX IDX_B13BA86A232519D5 ON resposta_quiz (nivelamento_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE notas_quiz');
        $this->addSql('ALTER TABLE resposta_quiz DROP FOREIGN KEY FK_B13BA86A232519D5');
        $this->addSql('DROP INDEX IDX_B13BA86A232519D5 ON resposta_quiz');
        $this->addSql('ALTER TABLE resposta_quiz DROP nivelamento_id');
    }
}
