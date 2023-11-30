<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210908133535 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE matricula_aulas_assistidas (matricula_id VARCHAR(255) NOT NULL, aula_id VARCHAR(255) NOT NULL, INDEX IDX_71C2B52715C84B52 (matricula_id), INDEX IDX_71C2B527AD1A1255 (aula_id), PRIMARY KEY(matricula_id, aula_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE matricula_aulas_assistidas ADD CONSTRAINT FK_71C2B52715C84B52 FOREIGN KEY (matricula_id) REFERENCES matricula (id)');
        $this->addSql('ALTER TABLE matricula_aulas_assistidas ADD CONSTRAINT FK_71C2B527AD1A1255 FOREIGN KEY (aula_id) REFERENCES aula (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE matricula_aulas_assistidas');
    }
}
