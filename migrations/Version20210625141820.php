<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210625141820 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE nivelamento (id VARCHAR(255) NOT NULL, user_id VARCHAR(255) DEFAULT NULL, created_by_id VARCHAR(255) DEFAULT NULL, last_updated_by VARCHAR(255) DEFAULT NULL, nome VARCHAR(255) NOT NULL, url_call_back LONGTEXT DEFAULT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_69965F5AA76ED395 (user_id), INDEX IDX_69965F5AB03A8386 (created_by_id), INDEX IDX_69965F5AFF8A180B (last_updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quizes_nivelamento (quiz_id VARCHAR(255) NOT NULL, nivelamento_id VARCHAR(255) NOT NULL, ordem INT NOT NULL, pontuacao_minima DOUBLE PRECISION DEFAULT NULL, INDEX IDX_17EC313D853CD175 (quiz_id), INDEX IDX_17EC313D232519D5 (nivelamento_id), PRIMARY KEY(quiz_id, nivelamento_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE nivelamento ADD CONSTRAINT FK_69965F5AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE nivelamento ADD CONSTRAINT FK_69965F5AB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE nivelamento ADD CONSTRAINT FK_69965F5AFF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE quizes_nivelamento ADD CONSTRAINT FK_17EC313D853CD175 FOREIGN KEY (quiz_id) REFERENCES quizes (id)');
        $this->addSql('ALTER TABLE quizes_nivelamento ADD CONSTRAINT FK_17EC313D232519D5 FOREIGN KEY (nivelamento_id) REFERENCES nivelamento (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quizes_nivelamento DROP FOREIGN KEY FK_17EC313D232519D5');
        $this->addSql('DROP TABLE nivelamento');
        $this->addSql('DROP TABLE quizes_nivelamento');
    }
}
