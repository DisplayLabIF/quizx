<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210215170315 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE curso ADD escola_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE curso ADD CONSTRAINT FK_CA3B40ECD786BBC9 FOREIGN KEY (escola_id) REFERENCES escola (id)');
        $this->addSql('CREATE INDEX IDX_CA3B40ECD786BBC9 ON curso (escola_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE curso DROP FOREIGN KEY FK_CA3B40ECD786BBC9');
        $this->addSql('DROP INDEX IDX_CA3B40ECD786BBC9 ON curso');
        $this->addSql('ALTER TABLE curso DROP escola_id');
        $this->addSql('ALTER TABLE escola ADD cursos_disponiveis_venda JSON NOT NULL');
    }
}
