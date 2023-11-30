<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221004180456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quiz_configuracao DROP obter_empresa, DROP redirecionar_usuario, DROP url_call_back, DROP obter_cnpj, DROP redirecionar_automaticamente');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quiz_configuracao ADD obter_empresa TINYINT(1) NOT NULL, ADD redirecionar_usuario TINYINT(1) NOT NULL, ADD url_call_back LONGTEXT DEFAULT NULL, ADD obter_cnpj TINYINT(1) NOT NULL, ADD redirecionar_automaticamente TINYINT(1) DEFAULT 1 NOT NULL');
    }
}
