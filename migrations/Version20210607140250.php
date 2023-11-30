<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210607140250 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quiz_configuracao ADD obter_cnpj TINYINT(1) NOT NULL, CHANGE obter_cpf_ou_cnpj obter_cpf TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE quiz_lead ADD cpf VARCHAR(255) DEFAULT NULL, CHANGE cpf_ou_cnpj cnpj VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quiz_configuracao ADD obter_cpf_ou_cnpj TINYINT(1) NOT NULL, DROP obter_cpf, DROP obter_cnpj');
        $this->addSql('ALTER TABLE quiz_lead ADD cpf_ou_cnpj VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP cnpj, DROP cpf');
    }
}
