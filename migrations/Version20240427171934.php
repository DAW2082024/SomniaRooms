<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240427171934 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE room_availability (day DATE NOT NULL, room_category_id INT NOT NULL, num_available INT NOT NULL, PRIMARY KEY(day, room_category_id))');
        $this->addSql('CREATE INDEX IDX_89C5BA2C67333DD ON room_availability (room_category_id)');
        $this->addSql('ALTER TABLE room_availability ADD CONSTRAINT FK_89C5BA2C67333DD FOREIGN KEY (room_category_id) REFERENCES room_category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_718569538A90ABA9 ON config_variable (key)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE room_availability DROP CONSTRAINT FK_89C5BA2C67333DD');
        $this->addSql('DROP TABLE room_availability');
        $this->addSql('DROP INDEX UNIQ_718569538A90ABA9');
    }
}
