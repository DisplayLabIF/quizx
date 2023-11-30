<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210506165710 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE questoes DROP FOREIGN KEY FK_2B93B1AF16560264');
        $this->addSql('DROP INDEX IDX_2B93B1AF16560264 ON questoes');
        $this->addSql('ALTER TABLE questoes DROP area_conhecimento_id, DROP ordem');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE questoes ADD area_conhecimento_id INT DEFAULT NULL, ADD ordem INT DEFAULT NULL');
        $this->addSql('ALTER TABLE questoes ADD CONSTRAINT FK_2B93B1AF16560264 FOREIGN KEY (area_conhecimento_id) REFERENCES sub_areas_conhecimento (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_2B93B1AF16560264 ON questoes (area_conhecimento_id)');
    }
}
