<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240626083258 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE comment (id INT NOT NULL, description TEXT DEFAULT NULL, rating DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE category DROP CONSTRAINT fk_64c19c14584665a');
        $this->addSql('DROP INDEX idx_64c19c14584665a');
        $this->addSql('ALTER TABLE category RENAME COLUMN product_id TO product_id_id');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1DE18E50B FOREIGN KEY (product_id_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_64C19C1DE18E50B ON category (product_id_id)');
        $this->addSql('ALTER TABLE product ADD comment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD stock INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD created_date DATE NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADF8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D34A04ADF8697D13 ON product (comment_id)');
        $this->addSql('ALTER TABLE "user" ADD comment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8D93D649F8697D13 ON "user" (comment_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT FK_D34A04ADF8697D13');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649F8697D13');
        $this->addSql('DROP SEQUENCE comment_id_seq CASCADE');
        $this->addSql('DROP TABLE comment');
        $this->addSql('ALTER TABLE category DROP CONSTRAINT FK_64C19C1DE18E50B');
        $this->addSql('DROP INDEX IDX_64C19C1DE18E50B');
        $this->addSql('ALTER TABLE category RENAME COLUMN product_id_id TO product_id');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT fk_64c19c14584665a FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_64c19c14584665a ON category (product_id)');
        $this->addSql('DROP INDEX IDX_8D93D649F8697D13');
        $this->addSql('ALTER TABLE "user" DROP comment_id');
        $this->addSql('DROP INDEX IDX_D34A04ADF8697D13');
        $this->addSql('ALTER TABLE product DROP comment_id');
        $this->addSql('ALTER TABLE product DROP stock');
        $this->addSql('ALTER TABLE product DROP created_date');
    }
}
