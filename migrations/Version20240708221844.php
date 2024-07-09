<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240708221844 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE articles (id VARCHAR(255) NOT NULL, category_id UUID NOT NULL, section_id UUID DEFAULT NULL, author_id VARCHAR(255) NOT NULL, main_image_id VARCHAR(255) NOT NULL, date_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, title VARCHAR(255) NOT NULL, content TEXT NOT NULL, value INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN articles.date_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE categories (id UUID NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE comments (id UUID NOT NULL, article_id VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, message TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5F9E962A7294869C ON comments (article_id)');
        $this->addSql('COMMENT ON COLUMN comments.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE images (id VARCHAR(255) NOT NULL, is_used BOOLEAN NOT NULL, name_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE sections (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE users (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A7294869C FOREIGN KEY (article_id) REFERENCES articles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE comments DROP CONSTRAINT FK_5F9E962A7294869C');
        $this->addSql('DROP TABLE articles');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE sections');
        $this->addSql('DROP TABLE users');
    }
}
