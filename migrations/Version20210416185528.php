<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210416185528 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quizes ADD user_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE quizes ADD CONSTRAINT FK_8E40FA13A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8E40FA13A76ED395 ON quizes (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quizes DROP FOREIGN KEY FK_8E40FA13A76ED395');
        $this->addSql('DROP INDEX IDX_8E40FA13A76ED395 ON quizes');
        $this->addSql('ALTER TABLE quizes DROP user_id');
    }
}
