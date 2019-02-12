<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190212091004 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE historique_stock DROP FOREIGN KEY FK_5B68C134A605127');
        $this->addSql('ALTER TABLE historique_stock DROP FOREIGN KEY FK_5B68C13A76ED395');
        $this->addSql('ALTER TABLE historique_stock DROP FOREIGN KEY FK_5B68C13DCD6110');
        $this->addSql('DROP INDEX IDX_5B68C134A605127 ON historique_stock');
        $this->addSql('DROP INDEX IDX_5B68C13DCD6110 ON historique_stock');
        $this->addSql('DROP INDEX IDX_5B68C13A76ED395 ON historique_stock');
        $this->addSql('ALTER TABLE historique_stock ADD user VARCHAR(255) NOT NULL, ADD modification VARCHAR(255) NOT NULL, ADD product VARCHAR(255) NOT NULL, DROP stock_id, DROP modification_id, DROP user_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE historique_stock ADD stock_id INT DEFAULT NULL, ADD modification_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL, DROP user, DROP modification, DROP product');
        $this->addSql('ALTER TABLE historique_stock ADD CONSTRAINT FK_5B68C134A605127 FOREIGN KEY (modification_id) REFERENCES modification (id)');
        $this->addSql('ALTER TABLE historique_stock ADD CONSTRAINT FK_5B68C13A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE historique_stock ADD CONSTRAINT FK_5B68C13DCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id)');
        $this->addSql('CREATE INDEX IDX_5B68C134A605127 ON historique_stock (modification_id)');
        $this->addSql('CREATE INDEX IDX_5B68C13DCD6110 ON historique_stock (stock_id)');
        $this->addSql('CREATE INDEX IDX_5B68C13A76ED395 ON historique_stock (user_id)');
    }
}
