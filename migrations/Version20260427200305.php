<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260427200305 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE permission_type (id INT NOT NULL, description VARCHAR(60) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user_permission (id INT AUTO_INCREMENT NOT NULL, user_id BINARY(16) NOT NULL, permission_type_id INT NOT NULL, INDEX IDX_472E5446A76ED395 (user_id), INDEX IDX_472E5446F25D6DC4 (permission_type_id), UNIQUE INDEX uk_user_permission (user_id, permission_type_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE user_permission ADD CONSTRAINT FK_472E5446A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_permission ADD CONSTRAINT FK_472E5446F25D6DC4 FOREIGN KEY (permission_type_id) REFERENCES permission_type (id)');
        $this->addSql('SET FOREIGN_KEY_CHECKS=0');
        $this->addSql('ALTER TABLE report_status CHANGE id id INT NOT NULL');
        $this->addSql('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_permission DROP FOREIGN KEY FK_472E5446A76ED395');
        $this->addSql('ALTER TABLE user_permission DROP FOREIGN KEY FK_472E5446F25D6DC4');
        $this->addSql('DROP TABLE permission_type');
        $this->addSql('DROP TABLE user_permission');
        $this->addSql('SET FOREIGN_KEY_CHECKS=0');
        $this->addSql('ALTER TABLE report_status CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('SET FOREIGN_KEY_CHECKS=1');
    }
}
