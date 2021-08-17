<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210813124758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE associations DROP FOREIGN KEY FK_8921C4B167B3B43D');
        $this->addSql('DROP INDEX IDX_8921C4B167B3B43D ON associations');
        $this->addSql('ALTER TABLE associations DROP users_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE associations ADD users_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE associations ADD CONSTRAINT FK_8921C4B167B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_8921C4B167B3B43D ON associations (users_id)');
    }
}
