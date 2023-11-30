<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210212124313 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE curso ADD imagem_curso LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE turma ADD disponivel_matricula TINYINT(1) NOT NULL, ADD cobrar_matricula TINYINT(1) NOT NULL, ADD condicao_especial_assinatura TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE curso DROP imagem_curso');
        $this->addSql('ALTER TABLE turma DROP disponivel_matricula, DROP cobrar_matricula, DROP condicao_especial_assinatura');
    }
}
