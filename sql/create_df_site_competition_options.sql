CREATE TABLE `df_site_competition_part` (
  `idx` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `competition_idx` int NOT NULL COMMENT '대회IDX',
  `f_part` varchar(50) NOT NULL COMMENT '참가부문',
  PRIMARY KEY (`idx`),
  KEY `comp_idx` (`competition_idx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='대회 참가부문 옵션';

CREATE TABLE `df_site_competition_field` (
  `idx` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `competition_idx` int NOT NULL COMMENT '대회IDX',
  `f_field` varchar(50) NOT NULL COMMENT '종목분야',
  PRIMARY KEY (`idx`),
  KEY `comp_idx` (`competition_idx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='대회 종목분야 옵션';

CREATE TABLE `df_site_competition_event` (
  `idx` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `competition_idx` int NOT NULL COMMENT '대회IDX',
  `f_event` varchar(100) NOT NULL COMMENT '참가종목',
  PRIMARY KEY (`idx`),
  KEY `comp_idx` (`competition_idx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='대회 참가종목 옵션';
