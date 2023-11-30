<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210414194503 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE especialidades (id INT AUTO_INCREMENT NOT NULL, sub_area_id INT DEFAULT NULL, created_by_id VARCHAR(255) DEFAULT NULL, last_updated_by VARCHAR(255) DEFAULT NULL, codigo INT NOT NULL, nome VARCHAR(50) NOT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_1FFEFE9EC2D71C68 (sub_area_id), INDEX IDX_1FFEFE9EB03A8386 (created_by_id), INDEX IDX_1FFEFE9EFF8A180B (last_updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE grandes_areas (id INT AUTO_INCREMENT NOT NULL, created_by_id VARCHAR(255) DEFAULT NULL, last_updated_by VARCHAR(255) DEFAULT NULL, codigo INT NOT NULL, nome VARCHAR(50) NOT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_CDBFB376B03A8386 (created_by_id), INDEX IDX_CDBFB376FF8A180B (last_updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE questoes (id VARCHAR(255) NOT NULL, area_conhecimento_id INT DEFAULT NULL, created_by_id VARCHAR(255) DEFAULT NULL, last_updated_by VARCHAR(255) DEFAULT NULL, numero_questao INT NOT NULL, enunciado LONGTEXT NOT NULL, tipo VARCHAR(20) NOT NULL, valor NUMERIC(6, 2) DEFAULT NULL, link_video VARCHAR(255) DEFAULT NULL, nivel VARCHAR(255) DEFAULT NULL, ordem INT DEFAULT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_2B93B1AF16560264 (area_conhecimento_id), INDEX IDX_2B93B1AFB03A8386 (created_by_id), INDEX IDX_2B93B1AFFF8A180B (last_updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_questoes (quiz_id VARCHAR(255) NOT NULL, questao_id VARCHAR(255) NOT NULL, INDEX IDX_FD5748EA853CD175 (quiz_id), INDEX IDX_FD5748EACB1A8E7E (questao_id), PRIMARY KEY(quiz_id, questao_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sub_areas_conhecimento (id INT AUTO_INCREMENT NOT NULL, grande_area_id INT DEFAULT NULL, created_by_id VARCHAR(255) DEFAULT NULL, last_updated_by VARCHAR(255) DEFAULT NULL, codigo INT NOT NULL, nome VARCHAR(50) NOT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_9F28B8AD31CB16D1 (grande_area_id), INDEX IDX_9F28B8ADB03A8386 (created_by_id), INDEX IDX_9F28B8ADFF8A180B (last_updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE especialidades ADD CONSTRAINT FK_1FFEFE9EC2D71C68 FOREIGN KEY (sub_area_id) REFERENCES sub_areas_conhecimento (id)');
        $this->addSql('ALTER TABLE especialidades ADD CONSTRAINT FK_1FFEFE9EB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE especialidades ADD CONSTRAINT FK_1FFEFE9EFF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE grandes_areas ADD CONSTRAINT FK_CDBFB376B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE grandes_areas ADD CONSTRAINT FK_CDBFB376FF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE questoes ADD CONSTRAINT FK_2B93B1AF16560264 FOREIGN KEY (area_conhecimento_id) REFERENCES sub_areas_conhecimento (id)');
        $this->addSql('ALTER TABLE questoes ADD CONSTRAINT FK_2B93B1AFB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE questoes ADD CONSTRAINT FK_2B93B1AFFF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE quiz_questoes ADD CONSTRAINT FK_FD5748EA853CD175 FOREIGN KEY (quiz_id) REFERENCES quizes (id)');
        $this->addSql('ALTER TABLE quiz_questoes ADD CONSTRAINT FK_FD5748EACB1A8E7E FOREIGN KEY (questao_id) REFERENCES questoes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sub_areas_conhecimento ADD CONSTRAINT FK_9F28B8AD31CB16D1 FOREIGN KEY (grande_area_id) REFERENCES grandes_areas (id)');
        $this->addSql('ALTER TABLE sub_areas_conhecimento ADD CONSTRAINT FK_9F28B8ADB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sub_areas_conhecimento ADD CONSTRAINT FK_9F28B8ADFF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sub_areas_conhecimento DROP FOREIGN KEY FK_9F28B8AD31CB16D1');
        $this->addSql('ALTER TABLE quiz_questoes DROP FOREIGN KEY FK_FD5748EACB1A8E7E');
        $this->addSql('ALTER TABLE especialidades DROP FOREIGN KEY FK_1FFEFE9EC2D71C68');
        $this->addSql('ALTER TABLE questoes DROP FOREIGN KEY FK_2B93B1AF16560264');
        $this->addSql('DROP TABLE especialidades');
        $this->addSql('DROP TABLE grandes_areas');
        $this->addSql('DROP TABLE questoes');
        $this->addSql('DROP TABLE quiz_questoes');
        $this->addSql('DROP TABLE sub_areas_conhecimento');
    }
}
