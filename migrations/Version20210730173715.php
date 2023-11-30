<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210730173715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE campos_personalizados (id VARCHAR(255) NOT NULL, created_by_id VARCHAR(255) DEFAULT NULL, last_updated_by VARCHAR(255) DEFAULT NULL, nome VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, tipo VARCHAR(255) NOT NULL, fonte_externa TINYINT(1) NOT NULL, endpoint_fonte_externa LONGTEXT NOT NULL, depende_outro_campo TINYINT(1) NOT NULL, campo_dependente VARCHAR(255) NOT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, INDEX IDX_D6EB4EA8B03A8386 (created_by_id), INDEX IDX_D6EB4EA8FF8A180B (last_updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE campos_personalizados_configuracao_nivelamento (configuracao_nivelamento_id VARCHAR(255) NOT NULL, campo_personalizado_id VARCHAR(255) NOT NULL, INDEX IDX_8F9697C494EEA23D (configuracao_nivelamento_id), INDEX IDX_8F9697C4FFDC0C6B (campo_personalizado_id), PRIMARY KEY(configuracao_nivelamento_id, campo_personalizado_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE campos_personalizados_configuracao_quiz (configuracao_quiz_id VARCHAR(255) NOT NULL, campo_personalizado_id VARCHAR(255) NOT NULL, INDEX IDX_3A3E95D95755D334 (configuracao_quiz_id), INDEX IDX_3A3E95D9FFDC0C6B (campo_personalizado_id), PRIMARY KEY(configuracao_quiz_id, campo_personalizado_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE campos_personalizados ADD CONSTRAINT FK_D6EB4EA8B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE campos_personalizados ADD CONSTRAINT FK_D6EB4EA8FF8A180B FOREIGN KEY (last_updated_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE campos_personalizados_configuracao_nivelamento ADD CONSTRAINT FK_8F9697C494EEA23D FOREIGN KEY (configuracao_nivelamento_id) REFERENCES nivelamento_configuracao (id)');
        $this->addSql('ALTER TABLE campos_personalizados_configuracao_nivelamento ADD CONSTRAINT FK_8F9697C4FFDC0C6B FOREIGN KEY (campo_personalizado_id) REFERENCES campos_personalizados (id)');
        $this->addSql('ALTER TABLE campos_personalizados_configuracao_quiz ADD CONSTRAINT FK_3A3E95D95755D334 FOREIGN KEY (configuracao_quiz_id) REFERENCES quiz_configuracao (id)');
        $this->addSql('ALTER TABLE campos_personalizados_configuracao_quiz ADD CONSTRAINT FK_3A3E95D9FFDC0C6B FOREIGN KEY (campo_personalizado_id) REFERENCES campos_personalizados (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE campos_personalizados_configuracao_nivelamento DROP FOREIGN KEY FK_8F9697C4FFDC0C6B');
        $this->addSql('ALTER TABLE campos_personalizados_configuracao_quiz DROP FOREIGN KEY FK_3A3E95D9FFDC0C6B');
        $this->addSql('DROP TABLE campos_personalizados');
        $this->addSql('DROP TABLE campos_personalizados_configuracao_nivelamento');
        $this->addSql('DROP TABLE campos_personalizados_configuracao_quiz');
    }
}
