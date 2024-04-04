<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240110205636 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial migration for fossil catalog database';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE earth_age_series (id INT AUTO_INCREMENT NOT NULL, earth_age_system_id INT NOT NULL, name VARCHAR(255) NOT NULL, custom TINYINT(1) NOT NULL, INDEX IDX_2051C30D10CFAD95 (earth_age_system_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE earth_age_stage (id INT AUTO_INCREMENT NOT NULL, earth_age_series_id INT NOT NULL, name VARCHAR(255) NOT NULL, custom TINYINT(1) NOT NULL, INDEX IDX_596ACE7D9222B3AC (earth_age_series_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE earth_age_system (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, custom TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fossil (id INT AUTO_INCREMENT NOT NULL, show_in_gallery TINYINT(1) NOT NULL, number VARCHAR(255) NOT NULL, date_of_discovery DATE DEFAULT NULL, found_in_country VARCHAR(255) DEFAULT NULL, finding_place VARCHAR(255) DEFAULT NULL, coordinates VARCHAR(255) DEFAULT NULL, finding_notes LONGTEXT DEFAULT NULL, formation VARCHAR(255) DEFAULT NULL, stratigraphic_member VARCHAR(255) DEFAULT NULL, stratigraphic_notes LONGTEXT DEFAULT NULL, empire VARCHAR(255) DEFAULT NULL, tribe VARCHAR(255) DEFAULT NULL, class VARCHAR(255) DEFAULT NULL, taxonomic_order VARCHAR(255) DEFAULT NULL, family VARCHAR(255) DEFAULT NULL, genius VARCHAR(255) DEFAULT NULL, species VARCHAR(255) DEFAULT NULL, subspecies VARCHAR(255) DEFAULT NULL, size VARCHAR(255) DEFAULT NULL, taxonomy_notes LONGTEXT DEFAULT NULL, extra_fields JSON DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fossil_category (fossil_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_1773EE2BEC5FAAEB (fossil_id), INDEX IDX_1773EE2B12469DE2 (category_id), PRIMARY KEY(fossil_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fossil_tag (fossil_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_5F69BB4AEC5FAAEB (fossil_id), INDEX IDX_5F69BB4ABAD26311 (tag_id), PRIMARY KEY(fossil_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fossil_image (fossil_id INT NOT NULL, image_id INT NOT NULL, INDEX IDX_D6B37893EC5FAAEB (fossil_id), INDEX IDX_D6B378933DA5256D (image_id), PRIMARY KEY(fossil_id, image_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fossil_form_field (id INT AUTO_INCREMENT NOT NULL, field_order INT NOT NULL, field_name VARCHAR(255) NOT NULL, field_label VARCHAR(255) NOT NULL, field_type VARCHAR(255) NOT NULL, allow_blank TINYINT(1) NOT NULL, is_required_default TINYINT(1) NOT NULL, active TINYINT(1) NOT NULL, show_always TINYINT(1) NOT NULL, field_group VARCHAR(255) NOT NULL, show_in_overview TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, thumbnail_path VARCHAR(255) NOT NULL, show_in_gallery TINYINT(1) NOT NULL, is_main_image TINYINT(1) NOT NULL, mime_type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE earth_age_series ADD CONSTRAINT FK_2051C30D10CFAD95 FOREIGN KEY (earth_age_system_id) REFERENCES earth_age_system (id)');
        $this->addSql('ALTER TABLE earth_age_stage ADD CONSTRAINT FK_596ACE7D9222B3AC FOREIGN KEY (earth_age_series_id) REFERENCES earth_age_series (id)');
        $this->addSql('ALTER TABLE fossil_category ADD CONSTRAINT FK_1773EE2BEC5FAAEB FOREIGN KEY (fossil_id) REFERENCES fossil (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fossil_category ADD CONSTRAINT FK_1773EE2B12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fossil_tag ADD CONSTRAINT FK_5F69BB4AEC5FAAEB FOREIGN KEY (fossil_id) REFERENCES fossil (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fossil_tag ADD CONSTRAINT FK_5F69BB4ABAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fossil_image ADD CONSTRAINT FK_D6B37893EC5FAAEB FOREIGN KEY (fossil_id) REFERENCES fossil (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fossil_image ADD CONSTRAINT FK_D6B378933DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
    }
}
