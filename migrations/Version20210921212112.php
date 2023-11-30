<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210921212112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE campos_personalizados DROP FOREIGN KEY FK_D6EB4EA8A17A385C');
        $this->addSql('DROP INDEX UNIQ_D6EB4EA8A17A385C ON campos_personalizados');
        $this->addSql('ALTER TABLE campos_personalizados CHANGE campo_id campo_dependente_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE campos_personalizados ADD CONSTRAINT FK_D6EB4EA8C6D2874F FOREIGN KEY (campo_dependente_id) REFERENCES campos_personalizados (id)');
        $this->addSql('CREATE INDEX IDX_D6EB4EA8C6D2874F ON campos_personalizados (campo_dependente_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE campos_personalizados DROP FOREIGN KEY FK_D6EB4EA8C6D2874F');
        $this->addSql('DROP INDEX IDX_D6EB4EA8C6D2874F ON campos_personalizados');
        $this->addSql('ALTER TABLE campos_personalizados CHANGE campo_dependente_id campo_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE campos_personalizados ADD CONSTRAINT FK_D6EB4EA8A17A385C FOREIGN KEY (campo_id) REFERENCES campos_personalizados (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D6EB4EA8A17A385C ON campos_personalizados (campo_id)');
    }
}
