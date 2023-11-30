<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211214131559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE endereco DROP FOREIGN KEY FK_F8E0D60EB2DDF7F4');
        $this->addSql('DROP INDEX IDX_F8E0D60EB2DDF7F4 ON endereco');
        $this->addSql('ALTER TABLE endereco CHANGE aluno_id user_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE endereco ADD CONSTRAINT FK_F8E0D60EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_F8E0D60EA76ED395 ON endereco (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE endereco DROP FOREIGN KEY FK_F8E0D60EA76ED395');
        $this->addSql('DROP INDEX IDX_F8E0D60EA76ED395 ON endereco');
        $this->addSql('ALTER TABLE endereco CHANGE user_id aluno_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE endereco ADD CONSTRAINT FK_F8E0D60EB2DDF7F4 FOREIGN KEY (aluno_id) REFERENCES aluno (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_F8E0D60EB2DDF7F4 ON endereco (aluno_id)');
    }
}
