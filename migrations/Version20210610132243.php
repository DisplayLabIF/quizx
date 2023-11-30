<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210610132243 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE material ADD quiz_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE material ADD CONSTRAINT FK_7CBE7595853CD175 FOREIGN KEY (quiz_id) REFERENCES quizes (id)');
        $this->addSql('CREATE INDEX IDX_7CBE7595853CD175 ON material (quiz_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE material DROP FOREIGN KEY FK_7CBE7595853CD175');
        $this->addSql('DROP INDEX IDX_7CBE7595853CD175 ON material');
        $this->addSql('ALTER TABLE material DROP quiz_id');
    }
}
