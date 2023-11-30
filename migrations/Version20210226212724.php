<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210226212724 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE professor (id VARCHAR(255) NOT NULL, contato_id VARCHAR(255) DEFAULT NULL, cpf VARCHAR(14) DEFAULT NULL, rg VARCHAR(14) DEFAULT NULL, data_aniversario DATE DEFAULT NULL, sexo VARCHAR(1) DEFAULT NULL, orgao_expediror VARCHAR(30) DEFAULT NULL, estado_civil VARCHAR(20) DEFAULT NULL, INDEX IDX_790DD7E3B279BE46 (contato_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE professor ADD CONSTRAINT FK_790DD7E3B279BE46 FOREIGN KEY (contato_id) REFERENCES contato (id)');
        $this->addSql('ALTER TABLE professor ADD CONSTRAINT FK_790DD7E3BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE professor');
    }
}
