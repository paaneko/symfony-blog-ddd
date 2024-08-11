<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240811130132 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE articles ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE articles ALTER title TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE articles ALTER content TYPE TEXT');
        $this->addSql('ALTER TABLE articles ALTER category_id TYPE UUID');
        $this->addSql('ALTER TABLE articles ALTER section_id TYPE UUID');
        $this->addSql('ALTER TABLE articles ALTER author_id TYPE UUID');
        $this->addSql('ALTER TABLE articles ALTER main_image_id TYPE UUID');
        $this->addSql('ALTER TABLE articles ALTER views TYPE INT');
        $this->addSql('ALTER TABLE categories ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE categories ALTER name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE categories ALTER slug TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE comments DROP CONSTRAINT fk_5f9e962a7294869c');
        $this->addSql('DROP INDEX idx_5f9e962a7294869c');
        $this->addSql('ALTER TABLE comments ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE comments ALTER article_id TYPE UUID');
        $this->addSql('ALTER TABLE comments ALTER name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE comments ALTER email TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE comments ALTER message TYPE TEXT');
        $this->addSql('ALTER TABLE images ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE images ALTER name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE search_blog_index ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE search_blog_index ALTER article_identifier TYPE UUID');
        $this->addSql('ALTER TABLE sections ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE sections ALTER name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE users ADD password_hash VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE users ADD status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE users ADD join_confirm_token_value VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD join_confirm_token_expires DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE users ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE users ALTER name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE users ALTER email TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN users.join_confirm_token_expires IS \'(DC2Type:date_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE articles ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE articles ALTER title TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE articles ALTER content TYPE TEXT');
        $this->addSql('ALTER TABLE articles ALTER category_id TYPE UUID');
        $this->addSql('ALTER TABLE articles ALTER section_id TYPE UUID');
        $this->addSql('ALTER TABLE articles ALTER author_id TYPE UUID');
        $this->addSql('ALTER TABLE articles ALTER main_image_id TYPE UUID');
        $this->addSql('ALTER TABLE articles ALTER views TYPE INT');
        $this->addSql('ALTER TABLE comments ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE comments ALTER article_id TYPE UUID');
        $this->addSql('ALTER TABLE comments ALTER name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE comments ALTER email TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE comments ALTER message TYPE TEXT');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT fk_5f9e962a7294869c FOREIGN KEY (article_id) REFERENCES articles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_5f9e962a7294869c ON comments (article_id)');
        $this->addSql('ALTER TABLE images ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE images ALTER name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE categories ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE categories ALTER name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE categories ALTER slug TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE search_blog_index ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE search_blog_index ALTER article_identifier TYPE UUID');
        $this->addSql('ALTER TABLE sections ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE sections ALTER name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE users DROP password_hash');
        $this->addSql('ALTER TABLE users DROP status');
        $this->addSql('ALTER TABLE users DROP join_confirm_token_value');
        $this->addSql('ALTER TABLE users DROP join_confirm_token_expires');
        $this->addSql('ALTER TABLE users ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE users ALTER name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE users ALTER email TYPE VARCHAR(255)');
    }
}
