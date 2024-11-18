<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241118142558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, city_id INT NOT NULL, name VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, INDEX IDX_5E9E89CB8BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `member` (id INT AUTO_INCREMENT NOT NULL, site_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, administrator TINYINT(1) NOT NULL, active TINYINT(1) NOT NULL, INDEX IDX_70E4FA78F6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE output_member (output_id INT NOT NULL, member_id INT NOT NULL, INDEX IDX_A439C091DE097880 (output_id), INDEX IDX_A439C0917597D3FE (member_id), PRIMARY KEY(output_id, member_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE `member` ADD CONSTRAINT FK_70E4FA78F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE output_member ADD CONSTRAINT FK_A439C091DE097880 FOREIGN KEY (output_id) REFERENCES output (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE output_member ADD CONSTRAINT FK_A439C0917597D3FE FOREIGN KEY (member_id) REFERENCES `member` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE output ADD status_id INT NOT NULL, ADD organisator_id INT NOT NULL, ADD site_id INT NOT NULL, ADD location_id INT NOT NULL');
        $this->addSql('ALTER TABLE output ADD CONSTRAINT FK_CCDE149E6BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE output ADD CONSTRAINT FK_CCDE149EFFDD4EC8 FOREIGN KEY (organisator_id) REFERENCES `member` (id)');
        $this->addSql('ALTER TABLE output ADD CONSTRAINT FK_CCDE149EF6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE output ADD CONSTRAINT FK_CCDE149E64D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('CREATE INDEX IDX_CCDE149E6BF700BD ON output (status_id)');
        $this->addSql('CREATE INDEX IDX_CCDE149EFFDD4EC8 ON output (organisator_id)');
        $this->addSql('CREATE INDEX IDX_CCDE149EF6BD1646 ON output (site_id)');
        $this->addSql('CREATE INDEX IDX_CCDE149E64D218E ON output (location_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE output DROP FOREIGN KEY FK_CCDE149E64D218E');
        $this->addSql('ALTER TABLE output DROP FOREIGN KEY FK_CCDE149EFFDD4EC8');
        $this->addSql('ALTER TABLE output DROP FOREIGN KEY FK_CCDE149EF6BD1646');
        $this->addSql('ALTER TABLE output DROP FOREIGN KEY FK_CCDE149E6BF700BD');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB8BAC62AF');
        $this->addSql('ALTER TABLE `member` DROP FOREIGN KEY FK_70E4FA78F6BD1646');
        $this->addSql('ALTER TABLE output_member DROP FOREIGN KEY FK_A439C091DE097880');
        $this->addSql('ALTER TABLE output_member DROP FOREIGN KEY FK_A439C0917597D3FE');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE `member`');
        $this->addSql('DROP TABLE output_member');
        $this->addSql('DROP TABLE site');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP INDEX IDX_CCDE149E6BF700BD ON output');
        $this->addSql('DROP INDEX IDX_CCDE149EFFDD4EC8 ON output');
        $this->addSql('DROP INDEX IDX_CCDE149EF6BD1646 ON output');
        $this->addSql('DROP INDEX IDX_CCDE149E64D218E ON output');
        $this->addSql('ALTER TABLE output DROP status_id, DROP organisator_id, DROP site_id, DROP location_id');
    }
}
