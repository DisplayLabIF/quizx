<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210705175259 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resposta_quiz ADD lead_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE resposta_quiz ADD CONSTRAINT FK_B13BA86A55458D FOREIGN KEY (lead_id) REFERENCES quiz_lead (id)');
        $this->addSql('CREATE INDEX IDX_B13BA86A55458D ON resposta_quiz (lead_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resposta_quiz DROP FOREIGN KEY FK_B13BA86A55458D');
        $this->addSql('DROP INDEX IDX_B13BA86A55458D ON resposta_quiz');
        $this->addSql('ALTER TABLE resposta_quiz DROP lead_id');
    }
}
