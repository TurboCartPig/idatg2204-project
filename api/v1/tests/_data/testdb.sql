CREATE TABLE `car`
(
    `id`         int(11)                               NOT NULL,
    `make`       varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
    `model`      varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
    `model_year` year(4)                               NOT NULL,
    `mileage`    int(11)                               NOT NULL,
    `fuel`       varchar(20) COLLATE utf8mb4_danish_ci NOT NULL,
    `type`       varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
    `price`      int(11)                               NOT NULL,
    `dealer_id`  int(11) COLLATE utf8mb4_danish_ci     NOT NULL,
    `comment`    varchar(512) COLLATE utf8mb4_danish_ci DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_danish_ci;

INSERT INTO `car` (`id`, `make`, `model`, `model_year`, `mileage`, `fuel`, `type`, `price`, `dealer_id`, `comment`)
VALUES (1, 'Volkswagen', 'Passat', 2017, 97805, 'diesel', 'station wagon', 425000, 1, NULL),
       (2, 'Mazda', 'CX-3', 2019, 19777, 'petrol', 'suv', 378900, 5, 'Demo car'),
       (3, 'Volkswagen', 'UP!', 2017, 16551, 'electric', 'hatchback', 125000, 1, NULL),
       (4, 'Toyota', 'RAV4', 2019, 39661, 'hybrid', 'suv', 428900, 6, 'Towing hitch'),
       (5, 'Mercedes-Benz', 'C Class', 2004, 301204, 'diesel', 'sedan', 31707, 6, NULL),
       (6, 'Audi', 'Q3', 2020, 18516, 'diesel', 'suv', 624900, 7, 'Demo driven'),
       (7, 'Toyota', 'Corolla', 2020, 8738, 'hybrid', 'station wagon', 354900, 6, NULL),
       (8, 'Mazda', 'CX-3', 2019, 23100, 'petrol', 'suv', 289900, 5, NULL),
       (9, 'Volkswagen', 'Passat', 2019, 43162, 'diesel', 'station wagon', 375000, 7, NULL),
       (10, 'Volkswagen', 'Passat', 2019, 37815, 'diesel', 'station wagon', 362000, 3, NULL),
       (11, 'Toyota', 'RAV4', 2016, 80000, 'hybrid', 'suv', 326000, 9, NULL),
       (12, 'Toyota', 'Corolla', 2020, 20503, 'hybrid', 'station wagon', 359000, 5, 'Demobil'),
       (13, 'Volkswagen', 'UP!', 2016, 26000, 'electric', 'hatchback', 129000, 2, 'Demo car'),
       (14, 'Mercedes-Benz', 'C Class', 2011, 201952, 'hybrid', 'suv', 126000, 6, NULL),
       (15, 'Mercedes-Benz', 'E Class', 2010, 223387, 'diesel', 'sedan', 155000, 1, NULL),
       (16, 'Audi', 'A4', 2019, 36250, 'hybrid', 'station wagon', 364900, 9, NULL),
       (17, 'Audi', 'A3', 2014, 38044, 'hybrid', 'station wagon', 199000, 2, 'Tow hitch'),
       (18, 'Mazda', 'CX-30', 2020, 5800, 'hybrid', 'suv', 399000, 3, 'Flawless'),
       (19, 'BMW', 'X5', 2020, 26000, 'hybrid', 'suv', 204900, 15, 'Well equipped'),
       (20, 'Mitsubishi', 'Outlander', 2004, 168000, 'petrol', 'station wagon', 49500, 10, 'Well maintained');

CREATE TABLE `car_brand`
(
    `make`        char(20) COLLATE utf8mb4_danish_ci NOT NULL,
    `description` varchar(300) COLLATE utf8mb4_danish_ci DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_danish_ci;

INSERT INTO `car_brand` (`make`, `description`)
VALUES ('Ford', NULL);

CREATE TABLE `car_model`
(
    `make`        char(20) COLLATE utf8mb4_danish_ci NOT NULL,
    `model`       char(20) COLLATE utf8mb4_danish_ci NOT NULL,
    `description` varchar(300) COLLATE utf8mb4_danish_ci DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_danish_ci;

INSERT INTO `car_model` (`make`, `model`, `description`)
VALUES ('Ford', 'Fiesta', NULL);

CREATE TABLE `county`
(
    `no`   int(11)                               NOT NULL,
    `name` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_danish_ci;

INSERT INTO `county` (`no`, `name`)
VALUES (3, 'Oslo'),
       (11, 'Rogaland'),
       (15, 'Møre og Romsdal'),
       (18, 'Nordland'),
       (30, 'Viken'),
       (34, 'Innlandet'),
       (38, 'Vestfold og Telemark'),
       (42, 'Agder'),
       (46, 'Vestland'),
       (50, 'Trøndelag'),
       (54, 'Troms og Finnmark');

CREATE TABLE `dealer`
(
    `id`        int(11) COLLATE utf8mb4_danish_ci     NOT NULL,
    `city`      varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
    `county_no` int(11)                               NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_danish_ci;

INSERT INTO `dealer` (`id`, `city`, `county_no`)
VALUES (1, 'Bardufoss', 54),
       (2, 'Bodø', 18),
       (3, 'Elverum', 34),
       (4, 'Fredrikstad', 30),
       (5, 'Gjøvik', 34),
       (6, 'Hamar', 34),
       (7, 'Harstad', 54),
       (8, 'Jessheim', 30),
       (9, 'Kongsvinger', 34),
       (10, 'Lillehammer', 34),
       (11, 'Mo i Rana', 18),
       (12, 'Moss', 30),
       (13, 'Otta', 34),
       (14, 'Sarpsborg', 30),
       (15, 'Trondheim', 50),
       (16, 'Tromsø', 54),
       (17, 'Verdal', 50);

CREATE TABLE `auth_token`
(
    `token` char(64) COLLATE utf8mb4_danish_ci NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_danish_ci;

INSERT INTO `auth_token` (`token`)
VALUES ('7f38212946ddbd7aadba90192887c5538328bb77bf3756504a1e538226fa8f51');

ALTER TABLE `car`
    ADD PRIMARY KEY (`id`),
    ADD KEY `car_dealer_fk` (`dealer_id`);

ALTER TABLE `car`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `car_brand`
    ADD PRIMARY KEY (`make`);

ALTER TABLE `car_model`
    ADD PRIMARY KEY (`make`, `model`);

ALTER TABLE `county`
    ADD PRIMARY KEY (`no`);

ALTER TABLE `dealer`
    ADD PRIMARY KEY (`id`),
    ADD KEY `dealer_county_fk` (`county_no`);

ALTER TABLE `dealer`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `car`
    ADD CONSTRAINT `car_dealer_fk` FOREIGN KEY (`dealer_id`) REFERENCES `dealer` (`id`) ON UPDATE CASCADE;

ALTER TABLE `car_model`
    ADD CONSTRAINT `car_model_brand_fk` FOREIGN KEY (`make`) REFERENCES `car_brand` (`make`) ON UPDATE CASCADE;

ALTER TABLE `dealer`
    ADD CONSTRAINT `dealer_county_fk` FOREIGN KEY (`county_no`) REFERENCES `county` (`no`) ON UPDATE CASCADE;
COMMIT;