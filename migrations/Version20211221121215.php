<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211221121215 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sub_areas_conhecimento DROP FOREIGN KEY FK_9F28B8AD31CB16D1');
        $this->addSql('ALTER TABLE especialidades DROP FOREIGN KEY FK_1FFEFE9EC2D71C68');
        $this->addSql('DROP TABLE especialidades');
        $this->addSql('DROP TABLE grandes_areas');
        $this->addSql('DROP TABLE sub_areas_conhecimento');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE especialidades (id INT AUTO_INCREMENT NOT NULL, sub_area_id INT DEFAULT NULL, created_by_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, last_updated_by VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, codigo INT NOT NULL, nome VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_1FFEFE9EB03A8386 (created_by_id), INDEX IDX_1FFEFE9EC2D71C68 (sub_area_id), INDEX IDX_1FFEFE9EFF8A180B (last_updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE grandes_areas (id INT AUTO_INCREMENT NOT NULL, created_by_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, last_updated_by VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, codigo INT NOT NULL, nome VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_CDBFB376B03A8386 (created_by_id), INDEX IDX_CDBFB376FF8A180B (last_updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE sub_areas_conhecimento (id INT AUTO_INCREMENT NOT NULL, grande_area_id INT DEFAULT NULL, created_by_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, last_updated_by VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, codigo INT NOT NULL, nome VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_9F28B8AD31CB16D1 (grande_area_id), INDEX IDX_9F28B8ADB03A8386 (created_by_id), INDEX IDX_9F28B8ADFF8A180B (last_updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE especialidades ADD CONSTRAINT FK_1FFEFE9EB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE especialidades ADD CONSTRAINT FK_1FFEFE9EC2D71C68 FOREIGN KEY (sub_area_id) REFERENCES sub_areas_conhecimento (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE especialidades ADD CONSTRAINT FK_1FFEFE9EFF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE grandes_areas ADD CONSTRAINT FK_CDBFB376B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE grandes_areas ADD CONSTRAINT FK_CDBFB376FF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE sub_areas_conhecimento ADD CONSTRAINT FK_9F28B8AD31CB16D1 FOREIGN KEY (grande_area_id) REFERENCES grandes_areas (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE sub_areas_conhecimento ADD CONSTRAINT FK_9F28B8ADB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE sub_areas_conhecimento ADD CONSTRAINT FK_9F28B8ADFF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
