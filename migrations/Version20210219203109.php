<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210219203109 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matricula ADD user_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE matricula ADD CONSTRAINT FK_15DF1885A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_15DF1885A76ED395 ON matricula (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matricula DROP FOREIGN KEY FK_15DF1885A76ED395');
        $this->addSql('DROP INDEX IDX_15DF1885A76ED395 ON matricula');
        $this->addSql('ALTER TABLE matricula DROP user_id');
    }
}
