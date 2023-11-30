<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210909191620 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resposta_quiz ADD aluno_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE resposta_quiz ADD CONSTRAINT FK_B13BA86AB2DDF7F4 FOREIGN KEY (aluno_id) REFERENCES aluno (id)');
        $this->addSql('CREATE INDEX IDX_B13BA86AB2DDF7F4 ON resposta_quiz (aluno_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resposta_quiz DROP FOREIGN KEY FK_B13BA86AB2DDF7F4');
        $this->addSql('DROP INDEX IDX_B13BA86AB2DDF7F4 ON resposta_quiz');
        $this->addSql('ALTER TABLE resposta_quiz DROP aluno_id');
    }
}
