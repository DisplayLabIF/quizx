<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210521123650 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quiz_configuracao ADD mostrar_gabarito TINYINT(1) NOT NULL, ADD definir_nota_minima TINYINT(1) NOT NULL, ADD nota_minima NUMERIC(6, 2) DEFAULT NULL, ADD redirecionar_usuario TINYINT(1) NOT NULL, ADD url_call_back LONGTEXT DEFAULT NULL, CHANGE mostrar_nota mostrar_nota VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE quizes DROP url_call_back');
        $this->addSql('ALTER TABLE resposta_quiz_questoes CHANGE resposta resposta JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quiz_configuracao DROP mostrar_gabarito, DROP definir_nota_minima, DROP nota_minima, DROP redirecionar_usuario, DROP url_call_back, CHANGE mostrar_nota mostrar_nota TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE quizes ADD url_call_back TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE resposta_quiz_questoes CHANGE resposta resposta LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
