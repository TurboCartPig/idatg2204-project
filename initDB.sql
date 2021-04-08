CREATE DATABASE IF NOT EXISTS db_project;


CREATE TABLE IF NOT EXISTS db_project.temperature(
    temp VARCHAR(10) NOT NULL PRIMARY KEY
);

CREATE TABLE IF NOT EXISTS db_project.grip_system(
    grip VARCHAR(50) NOT NULL PRIMARY KEY
);

CREATE TABLE IF NOT EXISTS db_project.ski_model(
    model_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS db_project.ski_type(
    type_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS db_project.weight_class(
    class_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    min_weight INT NOT NULL,
    max_weight INT NOT NULL
);

CREATE TABLE IF NOT EXISTS db_project.size_class(
    class_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    size INT NOT NULL
);

CREATE TABLE IF NOT EXISTS db_project.ski(
    ski_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    temp_class VARCHAR(10) NOT NULL REFERENCES temperature(temp),
    grip VARCHAR(50) NOT NULL REFERENCES grip_system(grip),
    description VARCHAR(500),
    historical BOOLEAN,
    photo LONGBLOB,
    msrp INT NOT NULL,
    type INT NOT NULL REFERENCES ski_type(type_id),
    model INT NOT NULL REFERENCES ski_model(model_id),
    weight INT NOT NULL REFERENCES weight_class(class_id),
    size INT NOT NULL REFERENCES  size_class(class_id)
);


CREATE TABLE IF NOT EXISTS db_project.order_state(
    state VARCHAR(50) NOT NULL PRIMARY KEY
);

CREATE TABLE IF NOT EXISTS db_project.order(
    order_number INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    parent_number INT REFERENCES `order`(order_number),
	customer_id INT REFERENCES `customer`(id),
    total_price INT NOT NULL,
    order_state VARCHAR(50) REFERENCES order_state(state)
);

CREATE TABLE IF NOT EXISTS db_project.skiis_in_order(
    order_number INT NOT NULL REFERENCES `order`(order_number),
    ski_id INT NOT NULL REFERENCES ski(ski_id),
    quantity INT NOT NULL,
    PRIMARY KEY (order_number,ski_id)
);

CREATE TABLE IF NOT EXISTS db_project.transporter(
    company_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS db_project.address(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    street VARCHAR(50) NOT NULL,
    number INT NOT NULL,
    postal_code INT NOT NULL,
    city VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS db_project.shipment_state(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    state VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS db_project.shipment(
    shipment_number INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    address_id INT NOT NULL REFERENCES address(id),
    pickup_date DATE NOT NULL,
    shipment_state INT NOT NULL REFERENCES shipment_state(id),
    order_number INT NOT NULL REFERENCES `order`(order_number),
    transporter_id INT NOT NULL REFERENCES transporter(company_id),
    driver_id INT NOT NULL
);


CREATE TABLE IF NOT EXISTS db_project.employee_role(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    role VARCHAR(25) NOT NULL
);

CREATE TABLE IF NOT EXISTS db_project.employee(
    number INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    role INT NOT NULL REFERENCES employee_role(id)
);

CREATE TABLE IF NOT EXISTS db_project.customer(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    customer_rep INT NOT NULL REFERENCES employee(number)
);

CREATE TABLE IF NOT EXISTS db_project.customer_franchise(
    id INT NOT NULL PRIMARY KEY REFERENCES customer(id),
    name VARCHAR(50) NOT NULL,
    address INT NOT NULL REFERENCES address(id),
    buying_price INT NOT NULL,
    store_information VARCHAR(500)
);

CREATE TABLE IF NOT EXISTS db_project.individual_store(
    id INT NOT NULL PRIMARY KEY REFERENCES customer(id),
    franchise_id INT REFERENCES customer_franchise(id),
    name VARCHAR(50) NOT NULL,
    address INT NOT NULL REFERENCES address(id),
    buying_price INT NOT NULL
);

CREATE TABLE IF NOT EXISTS db_project.teams_skier(
    id INT NOT NULL PRIMARY KEY REFERENCES customer(id),
    name VARCHAR(50) NOT NULL,
    date_of_birth DATE NOT NULL,
    club VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS  db_project.production_plan(
  num_of_skies INT NOT NULL,
  ski_type INT NOT NULL REFERENCES ski_type(type_id),
  manager INT NOT NULL REFERENCES employee(number),
  PRIMARY KEY (num_of_skies,ski_type)
);

INSERT INTO `customer` (`id`, `start_date`, `end_date`, `customer_rep`) VALUES
(1, '2020-01-01', '2021-01-01', 2),
(2, '1990-01-01', '1995-01-01', 3),
(3, '2002-01-01', '2005-01-01', 4),
(4, '2006-01-01', '2010-01-01', 4);


INSERT INTO `employee` (`number`, `name`, `role`) VALUES
(1, 'Gunnar', 1);

INSERT INTO `employee_role` (`id`, `role`) VALUES
(1, 'manager');

INSERT INTO `order` (`order_number`, `parent_number`, `customer_id`, `total_price`, `order_state`) VALUES
(1, 2, 1, 120000, 'In production'),
(2, 2, 1, 234000, 'Ready for shipping'),
(3, 1, 2, 320000, 'In production'),
(4, 1, 2, 120000, 'Ready for shipping'),
(5, 3, 3, 410000, 'In production'),
(6, 3, 3, 120000, 'Ready for shipping'),
(7, 4, 4, 210000, 'In production'),
(8, 4, 4, 110000, 'Ready for shipping');

INSERT INTO `order_state` (`state`) VALUES
('In production'),
('Ready for shipping');

INSERT INTO `production_plan` (`num_of_skies`, `ski_type`, `manager`) VALUES
(200, 2, 1),
(500, 1, 1);

INSERT INTO `ski_type` (`type_id`, `name`) VALUES
(1, 'classic'),
(2, 'sprint');