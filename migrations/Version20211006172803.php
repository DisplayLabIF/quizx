<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211006172803 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nivelamento_configuracao ADD redirecionar_automaticamente TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE quiz_configuracao ADD redirecionar_automaticamente TINYINT(1) DEFAULT \'1\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nivelamento_configuracao DROP redirecionar_automaticamente');
        $this->addSql('ALTER TABLE quiz_configuracao DROP redirecionar_automaticamente');
    }
}
