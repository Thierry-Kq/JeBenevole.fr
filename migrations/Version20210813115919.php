<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210813115919 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE offers (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, zip INT NOT NULL, city VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL, longitude DOUBLE PRECISION DEFAULT NULL, latitude DOUBLE PRECISION DEFAULT NULL, is_published TINYINT(1) NOT NULL, is_actived TINYINT(1) NOT NULL, is_urgent TINYINT(1) NOT NULL, is_deleted TINYINT(1) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, status TINYINT(1) NOT NULL, date_start DATETIME NOT NULL, date_end DATETIME NOT NULL, file VARCHAR(255) DEFAULT NULL, recommended TINYINT(1) NOT NULL, contact_external_name VARCHAR(255) DEFAULT NULL, contact_external_email VARCHAR(255) DEFAULT NULL, contact_external_tel VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE offers');
    }
}
