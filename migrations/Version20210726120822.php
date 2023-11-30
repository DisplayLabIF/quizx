<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210726120822 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE professor ADD escola_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE professor ADD CONSTRAINT FK_790DD7E3D786BBC9 FOREIGN KEY (escola_id) REFERENCES escola (id)');
        $this->addSql('CREATE INDEX IDX_790DD7E3D786BBC9 ON professor (escola_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE professor DROP FOREIGN KEY FK_790DD7E3D786BBC9');
        $this->addSql('DROP INDEX IDX_790DD7E3D786BBC9 ON professor');
        $this->addSql('ALTER TABLE professor DROP escola_id');
    }
}
