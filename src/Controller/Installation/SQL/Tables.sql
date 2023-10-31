CREATE TABLE IF NOT EXISTS `user`
(
    id       INT AUTO_INCREMENT NOT NULL,
    email    VARCHAR(180) NOT NULL,
    roles    JSON         NOT NULL,
    password VARCHAR(255) NOT NULL,
    UNIQUE INDEX UNIQ_8D93D649E7927C74 (email),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS messenger_messages
(
    id           BIGINT AUTO_INCREMENT NOT NULL,
    body         LONGTEXT     NOT NULL,
    headers      LONGTEXT     NOT NULL,
    queue_name   VARCHAR(190) NOT NULL,
    created_at   DATETIME     NOT NULL COMMENT '(DC2Type:datetime_immutable)',
    available_at DATETIME     NOT NULL COMMENT '(DC2Type:datetime_immutable)',
    delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
    INDEX        IDX_75EA56E0FB7336F0 (queue_name),
    INDEX        IDX_75EA56E0E3BD61CE (available_at),
    INDEX        IDX_75EA56E016BA31DB (delivered_at),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;