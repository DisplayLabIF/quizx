<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210917111422 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nivelamento_configuracao CHANGE configurar_land_page configurar_land_page TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE quiz_configuracao CHANGE configurar_land_page configurar_land_page TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE resposta_quiz ADD resposta_corrigida TINYINT(1) DEFAULT \'0\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nivelamento_configuracao CHANGE configurar_land_page configurar_land_page TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE quiz_configuracao CHANGE configurar_land_page configurar_land_page TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE resposta_quiz DROP resposta_corrigida');
    }
}
