<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210510133634 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quiz_configuracao RENAME INDEX idx_3f4440d2b03a8386 TO IDX_E0337669B03A8386');
        $this->addSql('ALTER TABLE quiz_configuracao RENAME INDEX idx_3f4440d2ff8a180b TO IDX_E0337669FF8A180B');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quiz_configuracao RENAME INDEX idx_e0337669b03a8386 TO IDX_3F4440D2B03A8386');
        $this->addSql('ALTER TABLE quiz_configuracao RENAME INDEX idx_e0337669ff8a180b TO IDX_3F4440D2FF8A180B');
    }
}
