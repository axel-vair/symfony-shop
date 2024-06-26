<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240626122632 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP CONSTRAINT fk_64c19c1de18e50b');
        $this->addSql('DROP INDEX idx_64c19c1de18e50b');
        $this->addSql('ALTER TABLE category RENAME COLUMN product_id_id TO product_id');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C14584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_64C19C14584665A ON category (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE category DROP CONSTRAINT FK_64C19C14584665A');
        $this->addSql('DROP INDEX IDX_64C19C14584665A');
        $this->addSql('ALTER TABLE category RENAME COLUMN product_id TO product_id_id');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT fk_64c19c1de18e50b FOREIGN KEY (product_id_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_64c19c1de18e50b ON category (product_id_id)');
    }
}
