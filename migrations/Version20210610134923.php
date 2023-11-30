<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210610134923 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE material DROP FOREIGN KEY FK_7CBE7595853CD175');
        $this->addSql('DROP INDEX IDX_7CBE7595853CD175 ON material');
        $this->addSql('ALTER TABLE material CHANGE quiz_id configuracao_quiz_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE material ADD CONSTRAINT FK_7CBE75955755D334 FOREIGN KEY (configuracao_quiz_id) REFERENCES quiz_configuracao (id)');
        $this->addSql('CREATE INDEX IDX_7CBE75955755D334 ON material (configuracao_quiz_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE material DROP FOREIGN KEY FK_7CBE75955755D334');
        $this->addSql('DROP INDEX IDX_7CBE75955755D334 ON material');
        $this->addSql('ALTER TABLE material CHANGE configuracao_quiz_id quiz_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE material ADD CONSTRAINT FK_7CBE7595853CD175 FOREIGN KEY (quiz_id) REFERENCES quizes (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_7CBE7595853CD175 ON material (quiz_id)');
    }
}
