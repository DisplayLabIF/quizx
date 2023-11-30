<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210310132245 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE aula (id VARCHAR(255) NOT NULL, created_by_id VARCHAR(255) DEFAULT NULL, last_updated_by VARCHAR(255) DEFAULT NULL, nome VARCHAR(255) NOT NULL, tipo VARCHAR(255) NOT NULL, descricao LONGTEXT NOT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_31990A4B03A8386 (created_by_id), INDEX IDX_31990A4FF8A180B (last_updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE aula_presenca (id VARCHAR(255) NOT NULL, aula_id VARCHAR(255) DEFAULT NULL, matricula_id VARCHAR(255) DEFAULT NULL, created_by_id VARCHAR(255) DEFAULT NULL, last_updated_by VARCHAR(255) DEFAULT NULL, presente TINYINT(1) NOT NULL, view TINYINT(1) NOT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_A2C08A7EAD1A1255 (aula_id), INDEX IDX_A2C08A7E15C84B52 (matricula_id), INDEX IDX_A2C08A7EB03A8386 (created_by_id), INDEX IDX_A2C08A7EFF8A180B (last_updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE material (id VARCHAR(255) NOT NULL, curso_id VARCHAR(255) DEFAULT NULL, aula_id VARCHAR(255) DEFAULT NULL, created_by_id VARCHAR(255) DEFAULT NULL, last_updated_by VARCHAR(255) DEFAULT NULL, nome VARCHAR(255) NOT NULL, tipo VARCHAR(255) NOT NULL, file LONGTEXT NOT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_7CBE759587CB4A1F (curso_id), INDEX IDX_7CBE7595AD1A1255 (aula_id), INDEX IDX_7CBE7595B03A8386 (created_by_id), INDEX IDX_7CBE7595FF8A180B (last_updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE modulo (id VARCHAR(255) NOT NULL, curso_id VARCHAR(255) DEFAULT NULL, created_by_id VARCHAR(255) DEFAULT NULL, last_updated_by VARCHAR(255) DEFAULT NULL, nome VARCHAR(255) NOT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_ECF1CF3687CB4A1F (curso_id), INDEX IDX_ECF1CF36B03A8386 (created_by_id), INDEX IDX_ECF1CF36FF8A180B (last_updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE aula ADD CONSTRAINT FK_31990A4B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE aula ADD CONSTRAINT FK_31990A4FF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE aula_presenca ADD CONSTRAINT FK_A2C08A7EAD1A1255 FOREIGN KEY (aula_id) REFERENCES aula (id)');
        $this->addSql('ALTER TABLE aula_presenca ADD CONSTRAINT FK_A2C08A7E15C84B52 FOREIGN KEY (matricula_id) REFERENCES matricula (id)');
        $this->addSql('ALTER TABLE aula_presenca ADD CONSTRAINT FK_A2C08A7EB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE aula_presenca ADD CONSTRAINT FK_A2C08A7EFF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE material ADD CONSTRAINT FK_7CBE759587CB4A1F FOREIGN KEY (curso_id) REFERENCES curso (id)');
        $this->addSql('ALTER TABLE material ADD CONSTRAINT FK_7CBE7595AD1A1255 FOREIGN KEY (aula_id) REFERENCES aula (id)');
        $this->addSql('ALTER TABLE material ADD CONSTRAINT FK_7CBE7595B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE material ADD CONSTRAINT FK_7CBE7595FF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE modulo ADD CONSTRAINT FK_ECF1CF3687CB4A1F FOREIGN KEY (curso_id) REFERENCES curso (id)');
        $this->addSql('ALTER TABLE modulo ADD CONSTRAINT FK_ECF1CF36B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE modulo ADD CONSTRAINT FK_ECF1CF36FF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE aula_presenca DROP FOREIGN KEY FK_A2C08A7EAD1A1255');
        $this->addSql('ALTER TABLE material DROP FOREIGN KEY FK_7CBE7595AD1A1255');
        $this->addSql('DROP TABLE aula');
        $this->addSql('DROP TABLE aula_presenca');
        $this->addSql('DROP TABLE material');
        $this->addSql('DROP TABLE modulo');
    }
}
