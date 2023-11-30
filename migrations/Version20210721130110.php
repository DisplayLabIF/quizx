<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210721130110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE leads_quiz (lead_id VARCHAR(255) NOT NULL, quiz_id VARCHAR(255) NOT NULL, INDEX IDX_EFBE824B55458D (lead_id), INDEX IDX_EFBE824B853CD175 (quiz_id), PRIMARY KEY(lead_id, quiz_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE leads_nivelamento (lead_id VARCHAR(255) NOT NULL, nivelamento_id VARCHAR(255) NOT NULL, INDEX IDX_497E3BCB55458D (lead_id), INDEX IDX_497E3BCB232519D5 (nivelamento_id), PRIMARY KEY(lead_id, nivelamento_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE leads_quiz ADD CONSTRAINT FK_EFBE824B55458D FOREIGN KEY (lead_id) REFERENCES quiz_lead (id)');
        $this->addSql('ALTER TABLE leads_quiz ADD CONSTRAINT FK_EFBE824B853CD175 FOREIGN KEY (quiz_id) REFERENCES quizes (id)');
        $this->addSql('ALTER TABLE leads_nivelamento ADD CONSTRAINT FK_497E3BCB55458D FOREIGN KEY (lead_id) REFERENCES quiz_lead (id)');
        $this->addSql('ALTER TABLE leads_nivelamento ADD CONSTRAINT FK_497E3BCB232519D5 FOREIGN KEY (nivelamento_id) REFERENCES nivelamento (id)');
        $this->addSql('ALTER TABLE quiz_lead DROP FOREIGN KEY FK_E2609D90853CD175');
        $this->addSql('DROP INDEX IDX_E2609D90853CD175 ON quiz_lead');
        $this->addSql('ALTER TABLE quiz_lead DROP quiz_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE leads_quiz');
        $this->addSql('DROP TABLE leads_nivelamento');
        $this->addSql('ALTER TABLE quiz_lead ADD quiz_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE quiz_lead ADD CONSTRAINT FK_E2609D90853CD175 FOREIGN KEY (quiz_id) REFERENCES quizes (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_E2609D90853CD175 ON quiz_lead (quiz_id)');
    }
}
