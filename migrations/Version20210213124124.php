<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210213124124 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE curso ADD user_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE curso ADD CONSTRAINT FK_CA3B40ECA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_CA3B40ECA76ED395 ON curso (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE curso DROP FOREIGN KEY FK_CA3B40ECA76ED395');
        $this->addSql('DROP INDEX IDX_CA3B40ECA76ED395 ON curso');
        $this->addSql('ALTER TABLE curso DROP user_id');
        $this->addSql('ALTER TABLE escola CHANGE cursos_disponiveis_venda cursos_disponiveis_venda JSON DEFAULT NULL');
    }
}
