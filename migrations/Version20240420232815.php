<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240420232815 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE room_category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE room_category_details_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE room_category_photo_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE room_category (id INT NOT NULL, name VARCHAR(64) NOT NULL, description TEXT DEFAULT NULL, bed_type VARCHAR(64) DEFAULT NULL, max_guest_num INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE room_category_details (id INT NOT NULL, room_category_id INT NOT NULL, details_section VARCHAR(255) NOT NULL, detail_value VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3735FABB67333DD ON room_category_details (room_category_id)');
        $this->addSql('CREATE TABLE room_category_photo (id INT NOT NULL, room_category_id INT NOT NULL, path VARCHAR(255) NOT NULL, alt_text VARCHAR(255) DEFAULT NULL, kind VARCHAR(30) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B5843CAC67333DD ON room_category_photo (room_category_id)');
        $this->addSql('ALTER TABLE room_category_details ADD CONSTRAINT FK_3735FABB67333DD FOREIGN KEY (room_category_id) REFERENCES room_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE room_category_photo ADD CONSTRAINT FK_B5843CAC67333DD FOREIGN KEY (room_category_id) REFERENCES room_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE room_category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE room_category_details_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE room_category_photo_id_seq CASCADE');
        $this->addSql('ALTER TABLE room_category_details DROP CONSTRAINT FK_3735FABB67333DD');
        $this->addSql('ALTER TABLE room_category_photo DROP CONSTRAINT FK_B5843CAC67333DD');
        $this->addSql('DROP TABLE room_category');
        $this->addSql('DROP TABLE room_category_details');
        $this->addSql('DROP TABLE room_category_photo');
    }
}
