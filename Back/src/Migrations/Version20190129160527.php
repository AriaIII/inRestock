<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190129160527 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category CHANGE name name VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE town town VARCHAR(75) DEFAULT NULL');
        $this->addSql('ALTER TABLE supplier CHANGE town town VARCHAR(75) DEFAULT NULL');
        $this->addSql('ALTER TABLE role CHANGE name name VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE post CHANGE name name VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE historique_stock CHANGE post post VARCHAR(50) NOT NULL, CHANGE role role VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category CHANGE name name VARCHAR(15) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE historique_stock CHANGE post post VARCHAR(25) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE role role VARCHAR(25) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE post CHANGE name name VARCHAR(25) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE role CHANGE name name VARCHAR(25) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE supplier CHANGE town town VARCHAR(10) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE user CHANGE town town VARCHAR(60) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
