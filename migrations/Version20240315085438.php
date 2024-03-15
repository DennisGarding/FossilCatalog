<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240315085438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add settings table to database';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE settings (id INT AUTO_INCREMENT NOT NULL, banner_id INT DEFAULT NULL, brand VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_E545A0C5684EC833 (banner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE settings ADD CONSTRAINT FK_E545A0C5684EC833 FOREIGN KEY (banner_id) REFERENCES image (id)');
    }
}
