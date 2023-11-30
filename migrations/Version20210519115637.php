<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210519115637 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resposta_quiz_questoes ADD quiz_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE resposta_quiz_questoes ADD CONSTRAINT FK_CE4432B4853CD175 FOREIGN KEY (quiz_id) REFERENCES quizes (id)');
        $this->addSql('CREATE INDEX IDX_CE4432B4853CD175 ON resposta_quiz_questoes (quiz_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resposta_quiz_questoes DROP FOREIGN KEY FK_CE4432B4853CD175');
        $this->addSql('DROP INDEX IDX_CE4432B4853CD175 ON resposta_quiz_questoes');
        $this->addSql('ALTER TABLE resposta_quiz_questoes DROP quiz_id');
    }
}
