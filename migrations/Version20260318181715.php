<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260318181715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE horoscope (id BINARY(16) NOT NULL, start_date DATE NOT NULL, message VARCHAR(255) NOT NULL, luck_number INT NOT NULL, zodiac_id BINARY(16) NOT NULL, INDEX IDX_4CA15B46CE8CC3A4 (zodiac_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE login (id BINARY(16) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_AA08CB10E7927C74 (email), INDEX email_login_idx (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE login_user (id INT AUTO_INCREMENT NOT NULL, login_id BINARY(16) NOT NULL, user_id BINARY(16) NOT NULL, INDEX IDX_4BE15D0C5CB2E05D (login_id), INDEX IDX_4BE15D0CA76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE report (id BINARY(16) NOT NULL, month INT NOT NULL, year INT NOT NULL, requested_at DATE NOT NULL, completed_at DATE DEFAULT NULL, user_id BINARY(16) NOT NULL, status INT NOT NULL, INDEX IDX_C42F7784A76ED395 (user_id), INDEX IDX_C42F77847B00651C (status), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE report_status (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(16) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id BINARY(16) NOT NULL, name VARCHAR(60) NOT NULL, email VARCHAR(255) NOT NULL, family_name VARCHAR(60) NOT NULL, birth_date DATE NOT NULL, birth_time TIME DEFAULT NULL, zodiac_id BINARY(16) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649CE8CC3A4 (zodiac_id), INDEX email_login_idx (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE zodiac (id BINARY(16) NOT NULL, sign VARCHAR(60) NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE horoscope ADD CONSTRAINT FK_4CA15B46CE8CC3A4 FOREIGN KEY (zodiac_id) REFERENCES zodiac (id)');
        $this->addSql('ALTER TABLE login_user ADD CONSTRAINT FK_4BE15D0C5CB2E05D FOREIGN KEY (login_id) REFERENCES login (id)');
        $this->addSql('ALTER TABLE login_user ADD CONSTRAINT FK_4BE15D0CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F77847B00651C FOREIGN KEY (status) REFERENCES report_status (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649CE8CC3A4 FOREIGN KEY (zodiac_id) REFERENCES zodiac (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE horoscope DROP FOREIGN KEY FK_4CA15B46CE8CC3A4');
        $this->addSql('ALTER TABLE login_user DROP FOREIGN KEY FK_4BE15D0C5CB2E05D');
        $this->addSql('ALTER TABLE login_user DROP FOREIGN KEY FK_4BE15D0CA76ED395');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784A76ED395');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F77847B00651C');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649CE8CC3A4');
        $this->addSql('DROP TABLE horoscope');
        $this->addSql('DROP TABLE login');
        $this->addSql('DROP TABLE login_user');
        $this->addSql('DROP TABLE report');
        $this->addSql('DROP TABLE report_status');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE zodiac');
    }
}
