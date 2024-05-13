<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240508180113 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE booking_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE booking_customer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE booking_room_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE booking (id INT NOT NULL, arrival_date DATE NOT NULL, departure_date DATE NOT NULL, booking_time TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE booking_customer (id INT NOT NULL, booking_id INT NOT NULL, name VARCHAR(64) NOT NULL, surname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BA0D10843301C60 ON booking_customer (booking_id)');
        $this->addSql('CREATE TABLE booking_room (id INT NOT NULL, booking_id INT NOT NULL, room_category_id INT NOT NULL, guest_number INT NOT NULL, room_price INT NOT NULL, amount INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6A0E73D53301C60 ON booking_room (booking_id)');
        $this->addSql('CREATE INDEX IDX_6A0E73D567333DD ON booking_room (room_category_id)');
        $this->addSql('ALTER TABLE booking_customer ADD CONSTRAINT FK_BA0D10843301C60 FOREIGN KEY (booking_id) REFERENCES booking (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking_room ADD CONSTRAINT FK_6A0E73D53301C60 FOREIGN KEY (booking_id) REFERENCES booking (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking_room ADD CONSTRAINT FK_6A0E73D567333DD FOREIGN KEY (room_category_id) REFERENCES room_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE booking_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE booking_customer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE booking_room_id_seq CASCADE');
        $this->addSql('ALTER TABLE booking_customer DROP CONSTRAINT FK_BA0D10843301C60');
        $this->addSql('ALTER TABLE booking_room DROP CONSTRAINT FK_6A0E73D53301C60');
        $this->addSql('ALTER TABLE booking_room DROP CONSTRAINT FK_6A0E73D567333DD');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE booking_customer');
        $this->addSql('DROP TABLE booking_room');
    }
}
