<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240319154758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE fossil ADD ea_system_id INT DEFAULT NULL, ADD ea_series_id INT DEFAULT NULL, ADD ea_stage_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fossil ADD CONSTRAINT FK_AB0B899E24FD7241 FOREIGN KEY (ea_system_id) REFERENCES earth_age_system (id)');
        $this->addSql('ALTER TABLE fossil ADD CONSTRAINT FK_AB0B899EA6106C78 FOREIGN KEY (ea_series_id) REFERENCES earth_age_series (id)');
        $this->addSql('ALTER TABLE fossil ADD CONSTRAINT FK_AB0B899E9E706485 FOREIGN KEY (ea_stage_id) REFERENCES earth_age_stage (id)');
        $this->addSql('CREATE INDEX IDX_AB0B899E24FD7241 ON fossil (ea_system_id)');
        $this->addSql('CREATE INDEX IDX_AB0B899EA6106C78 ON fossil (ea_series_id)');
        $this->addSql('CREATE INDEX IDX_AB0B899E9E706485 ON fossil (ea_stage_id)');
    }
}
