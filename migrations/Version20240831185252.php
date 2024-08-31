<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240831185252 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE articles (id UUID NOT NULL, title VARCHAR(255) NOT NULL, content TEXT NOT NULL, category_id UUID NOT NULL, section_id UUID DEFAULT NULL, author_id UUID NOT NULL, main_image_id UUID NOT NULL, views INT NOT NULL, date_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN articles.date_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE categories (id UUID NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE comments (id UUID NOT NULL, article_id UUID NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, message TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN comments.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE followings (follower_id UUID NOT NULL, followee_id UUID NOT NULL, followed_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(follower_id, followee_id))');
        $this->addSql('COMMENT ON COLUMN followings.followed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE images (id UUID NOT NULL, name VARCHAR(255) NOT NULL, is_used BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE search_blog_index (id UUID NOT NULL, article_identifier UUID NOT NULL, article_title TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE sections (id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE users (id UUID NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password_hash VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, join_confirm_token_value VARCHAR(255) DEFAULT NULL, join_confirm_token_expires TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN users.join_confirm_token_expires IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE articles');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE followings');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE search_blog_index');
        $this->addSql('DROP TABLE sections');
        $this->addSql('DROP TABLE users');
    }
}
