<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210617164550 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quiz_personalizacao ADD background_image_apresentacao LONGTEXT DEFAULT NULL, ADD background_color_apresentacao LONGTEXT DEFAULT NULL, ADD background_image_questao LONGTEXT DEFAULT NULL, ADD background_color_questao LONGTEXT DEFAULT NULL, DROP background_type_apresentacao, DROP background_type_questao, DROP background_apresentacao, DROP background_questao');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quiz_personalizacao ADD background_type_apresentacao VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD background_type_questao VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD background_apresentacao LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD background_questao LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP background_image_apresentacao, DROP background_color_apresentacao, DROP background_image_questao, DROP background_color_questao');
    }
}
