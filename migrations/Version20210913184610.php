<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210913184610 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE escola ADD rd_station_client_id VARCHAR(255) DEFAULT NULL, ADD rd_station_client_secret VARCHAR(255) DEFAULT NULL, ADD rd_station_access_token VARCHAR(255) DEFAULT NULL, ADD rd_station_refresh_token VARCHAR(255) DEFAULT NULL, ADD rd_station_code VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE escola DROP rd_station_client_id, DROP rd_station_client_secret, DROP rd_station_access_token, DROP rd_station_refresh_token, DROP rd_station_code');
    }
}
