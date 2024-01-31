CREATE TABLE IF NOT EXISTS `user`
(
    id       INT AUTO_INCREMENT NOT NULL,
    email    VARCHAR(180)       NOT NULL,
    roles    JSON               NOT NULL,
    password VARCHAR(255)       NOT NULL,
    UNIQUE INDEX UNIQ_user__email (email),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE `utf8mb4_unicode_ci`
  ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS messenger_messages
(
    id           BIGINT AUTO_INCREMENT NOT NULL,
    body         LONGTEXT              NOT NULL,
    headers      LONGTEXT              NOT NULL,
    queue_name   VARCHAR(190)          NOT NULL,
    created_at   DATETIME              NOT NULL COMMENT '(DC2Type:datetime_immutable)',
    available_at DATETIME              NOT NULL COMMENT '(DC2Type:datetime_immutable)',
    delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
    INDEX IDX_messenger_messages__queue_name (queue_name),
    INDEX IDX_messenger_messages__available_at (available_at),
    INDEX IDX_messenger_messages__delivered_at (delivered_at),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE `utf8mb4_unicode_ci`
  ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS fossil
(
    id                   INT AUTO_INCREMENT NOT NULL,
    number               VARCHAR(255)       NOT NULL,
    date_of_discovery    DATE                        DEFAULT NULL,
    found_in_country     VARCHAR(255)                DEFAULT NULL,
    finding_place        VARCHAR(255)                DEFAULT NULL,
    coordinates          VARCHAR(255)                DEFAULT NULL,
    finding_notes        LONGTEXT                    DEFAULT NULL,
    earth_age_system_id  INT                         DEFAULT NULL,
    earth_age_series_id  INT                         DEFAULT NULL,
    earth_age_stage_id   INT                         DEFAULT NULL,
    formation            VARCHAR(255)                DEFAULT NULL,
    stratigraphic_member VARCHAR(255)                DEFAULT NULL,
    stratigraphic_notes  LONGTEXT                    DEFAULT NULL,
    empire               VARCHAR(255)                DEFAULT NULL,
    tribe                VARCHAR(255)                DEFAULT NULL,
    class                VARCHAR(255)                DEFAULT NULL,
    taxonomic_order      VARCHAR(255)                DEFAULT NULL,
    family               VARCHAR(255)                DEFAULT NULL,
    genius               VARCHAR(255)                DEFAULT NULL,
    species              VARCHAR(255)                DEFAULT NULL,
    subspecies           VARCHAR(255)                DEFAULT NULL,
    size                 VARCHAR(255)                DEFAULT NULL,
    taxonomy_notes       LONGTEXT                    DEFAULT NULL,
    show_in_gallery      TINYINT(1)         NOT NULL,
    extra_fields         JSON               NOT NULL DEFAULT (JSON_OBJECT()),
    created_at           DATETIME           NOT NULL COMMENT '(DC2Type:datetime_immutable)',
    updated_at           DATETIME           NOT NULL,
    UNIQUE INDEX UNIQ_IDX_fossil_number (number),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE `utf8mb4_unicode_ci`
  ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS tag
(
    id   INT AUTO_INCREMENT NOT NULL,
    name VARCHAR(255)       NOT NULL,
    UNIQUE INDEX UNIQ_IDX_tag_name (name),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE `utf8mb4_unicode_ci`
  ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS category
(
    id   INT AUTO_INCREMENT NOT NULL,
    name VARCHAR(255)       NOT NULL,
    UNIQUE INDEX UNIQ_IDX_category_name (name),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE `utf8mb4_unicode_ci`
  ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS fossil_tag
(
    fossil_id INT NOT NULL,
    tag_id    INT NOT NULL,
    INDEX IDX_fossil_tag__fossil_id (fossil_id),
    INDEX IDX_fossil_tag__tag_id (tag_id),
    PRIMARY KEY (fossil_id, tag_id)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE `utf8mb4_unicode_ci`
  ENGINE = InnoDB;

ALTER TABLE fossil_tag
    ADD CONSTRAINT FK_fossil_tag__fossil_id FOREIGN KEY (fossil_id) REFERENCES fossil (id);

ALTER TABLE fossil_tag
    ADD CONSTRAINT FK_fossil_tag__tag_id FOREIGN KEY (tag_id) REFERENCES tag (id);


CREATE TABLE IF NOT EXISTS fossil_category
(
    fossil_id   INT NOT NULL,
    category_id INT NOT NULL,
    INDEX IDX_1fossil_category__fossil_id (fossil_id),
    INDEX IDX_1fossil_category__category_id (category_id),
    PRIMARY KEY (fossil_id, category_id)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE `utf8mb4_unicode_ci`
  ENGINE = InnoDB;

ALTER TABLE fossil_category
    ADD CONSTRAINT FK_fossil_category__fossil_id FOREIGN KEY (fossil_id) REFERENCES fossil (id);

ALTER TABLE fossil_category
    ADD CONSTRAINT FK_fossil_category__category_id FOREIGN KEY (category_id) REFERENCES category (id);


CREATE TABLE image
(
    id              INT AUTO_INCREMENT NOT NULL,
    fossil_id       INT                NOT NULL,
    name            VARCHAR(255)       NOT NULL,
    path            VARCHAR(255)       NOT NULL,
    thumbnail_path  VARCHAR(255)       NOT NULL,
    mime_type       VARCHAR(255)       NOT NULL,
    show_in_gallery TINYINT(1)         NOT NULL,
    is_main_image   TINYINT(1)         NOT NULL,
    INDEX IDX_image_fossil_fossil_id (fossil_id),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE `utf8mb4_unicode_ci`
  ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS fossil_form_field
(
    id                  INT AUTO_INCREMENT NOT NULL,
    field_order         INT                NOT NULL,
    field_name          VARCHAR(255)       NOT NULL,
    field_label         VARCHAR(255)       NOT NULL,
    field_type          VARCHAR(255)       NOT NULL,
    allow_blank         TINYINT(1)         NOT NULL,
    is_required_default TINYINT(1)         NOT NULL,
    active              TINYINT(1)         NOT NULL,
    show_always         TINYINT(1)         NOT NULL,
    field_group         VARCHAR(255)       NOT NULL,
    show_in_overview    TINYINT(1)         NOT NULL,
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE `utf8mb4_unicode_ci`
  ENGINE = InnoDB;


CREATE TABLE earth_age_system
(
    id     INT AUTO_INCREMENT NOT NULL,
    name   VARCHAR(255)       NOT NULL,
    active TINYINT(1)         NOT NULL,
    INDEX IDX_earth_age_system__name (name),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE `utf8mb4_unicode_ci`
  ENGINE = InnoDB;


CREATE TABLE earth_age_series
(
    id                  INT AUTO_INCREMENT NOT NULL,
    earth_age_system_id INT DEFAULT NULL,
    name                VARCHAR(255)       NOT NULL,
    INDEX IDX_earth_age_series__earth_age_system_id (earth_age_system_id),
    INDEX IDX_earth_age_series__name (name),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE `utf8mb4_unicode_ci`
  ENGINE = InnoDB;


CREATE TABLE earth_age_stage
(
    id                  INT AUTO_INCREMENT NOT NULL,
    earth_age_series_id INT DEFAULT NULL,
    name                VARCHAR(255)       NOT NULL,
    INDEX IDX_earth_age_stage__earth_age_series_id (earth_age_series_id),
    INDEX IDX_earth_age_stage__name (name),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE `utf8mb4_unicode_ci`
  ENGINE = InnoDB;

ALTER TABLE earth_age_series
    ADD CONSTRAINT FK_earth_age_series__earth_age_system_id FOREIGN KEY (earth_age_system_id) REFERENCES earth_age_system (id);

ALTER TABLE earth_age_stage
    ADD CONSTRAINT FK_earth_age_stage_earth_age_series_id FOREIGN KEY (earth_age_series_id) REFERENCES earth_age_series (id);