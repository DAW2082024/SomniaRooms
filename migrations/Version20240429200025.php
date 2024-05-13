<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240429200025 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE fare_table_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE room_fare_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE fare_table (id INT NOT NULL, room_category_id INT NOT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8E43BE7067333DD ON fare_table (room_category_id)');
        $this->addSql('CREATE TABLE room_fare (id INT NOT NULL, fare_table_id INT NOT NULL, guest_number INT NOT NULL, fare_amount INT NOT NULL, day_type VARCHAR(2) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_35C9B213A7233EF2 ON room_fare (fare_table_id)');
        $this->addSql('ALTER TABLE fare_table ADD CONSTRAINT FK_8E43BE7067333DD FOREIGN KEY (room_category_id) REFERENCES room_category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE room_fare ADD CONSTRAINT FK_35C9B213A7233EF2 FOREIGN KEY (fare_table_id) REFERENCES fare_table (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE room_availability ALTER day TYPE VARCHAR(255)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE fare_table_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE room_fare_id_seq CASCADE');
        $this->addSql('ALTER TABLE fare_table DROP CONSTRAINT FK_8E43BE7067333DD');
        $this->addSql('ALTER TABLE room_fare DROP CONSTRAINT FK_35C9B213A7233EF2');
        $this->addSql('DROP TABLE fare_table');
        $this->addSql('DROP TABLE room_fare');
        $this->addSql('ALTER TABLE room_availability ALTER day TYPE DATE');
    }
}
