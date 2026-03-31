<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260331171707 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE app_request_log (id INT AUTO_INCREMENT NOT NULL, method VARCHAR(10) NOT NULL, endpoint VARCHAR(255) NOT NULL, status_code INT NOT NULL, response_time_ms INT NOT NULL, created_at DATETIME NOT NULL, INDEX idx_created_at (created_at), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE login DROP refresh_token, DROP refresh_token_expires_at');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE app_request_log');
        $this->addSql('ALTER TABLE login ADD refresh_token VARCHAR(512) DEFAULT NULL, ADD refresh_token_expires_at DATETIME DEFAULT NULL');
    }
}
