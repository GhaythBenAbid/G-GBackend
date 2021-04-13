<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210331164819 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE maintenance MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE maintenance DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE maintenance DROP id');
        $this->addSql('ALTER TABLE maintenance ADD PRIMARY KEY (product_id, technician_id, issue_id)');
        $this->addSql('ALTER TABLE validation MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE validation DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE validation DROP id');
        $this->addSql('ALTER TABLE validation ADD PRIMARY KEY (commercial_agent_id, product_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE maintenance ADD id INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE validation ADD id INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
    }
}
