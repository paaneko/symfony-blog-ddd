<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240811135717 extends AbstractMigration
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
        $this->addSql('ALTER TABLE users ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE users ALTER name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE users ALTER email TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE users ALTER status TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE users ALTER join_confirm_token_expires TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN users.join_confirm_token_expires IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE categories ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE categories ALTER name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE categories ALTER slug TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE search_blog_index ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE search_blog_index ALTER article_identifier TYPE UUID');
        $this->addSql('ALTER TABLE images ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE images ALTER name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE sections ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE sections ALTER name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE users ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE users ALTER name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE users ALTER email TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE users ALTER status TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE users ALTER join_confirm_token_expires TYPE DATE');
        $this->addSql('COMMENT ON COLUMN users.join_confirm_token_expires IS \'(DC2Type:date_immutable)\'');
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
    }
}
