<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210712171831 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE quiz_personalizacao_email (id VARCHAR(255) NOT NULL, created_by_id VARCHAR(255) DEFAULT NULL, last_updated_by VARCHAR(255) DEFAULT NULL, cor VARCHAR(7) NOT NULL, texto LONGTEXT DEFAULT NULL, texto_botao VARCHAR(50) DEFAULT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_F0932B1CB03A8386 (created_by_id), INDEX IDX_F0932B1CFF8A180B (last_updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE quiz_personalizacao_email ADD CONSTRAINT FK_F0932B1CB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE quiz_personalizacao_email ADD CONSTRAINT FK_F0932B1CFF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE quizes ADD personalizacao_email_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE quizes ADD CONSTRAINT FK_8E40FA133CE28874 FOREIGN KEY (personalizacao_email_id) REFERENCES quiz_personalizacao_email (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8E40FA133CE28874 ON quizes (personalizacao_email_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quizes DROP FOREIGN KEY FK_8E40FA133CE28874');
        $this->addSql('DROP TABLE quiz_personalizacao_email');
        $this->addSql('DROP INDEX UNIQ_8E40FA133CE28874 ON quizes');
        $this->addSql('ALTER TABLE quizes DROP personalizacao_email_id');
    }
}
