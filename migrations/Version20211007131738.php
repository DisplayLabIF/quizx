<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211007131738 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nivelamento_configuracao ADD nome_botao_redirecionar VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE quiz_configuracao ADD nome_botao_redirecionar VARCHAR(50) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nivelamento_configuracao DROP nome_botao_redirecionar');
        $this->addSql('ALTER TABLE quiz_configuracao DROP nome_botao_redirecionar');
    }
}
