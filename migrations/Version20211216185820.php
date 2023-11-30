<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211216185820 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE notificacao (id VARCHAR(255) NOT NULL, created_by_id VARCHAR(255) DEFAULT NULL, last_updated_by VARCHAR(255) DEFAULT NULL, lead_cadastrado TINYINT(1) DEFAULT \'1\' NOT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_5ACD9386B03A8386 (created_by_id), INDEX IDX_5ACD9386FF8A180B (last_updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notificacao ADD CONSTRAINT FK_5ACD9386B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notificacao ADD CONSTRAINT FK_5ACD9386FF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD notificacao_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6493C40C58C FOREIGN KEY (notificacao_id) REFERENCES notificacao (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6493C40C58C ON user (notificacao_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6493C40C58C');
        $this->addSql('DROP TABLE notificacao');
        $this->addSql('DROP INDEX UNIQ_8D93D6493C40C58C ON user');
        $this->addSql('ALTER TABLE user DROP notificacao_id');
    }
}
