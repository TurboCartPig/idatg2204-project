# Project for IDATG2204

# Database setup
You need to run the provided initDB.sql file to generate a new instance of the database of which this project utilizes.

One way of doing this is to simply copy/paste the entire file and paste it into the SQL field on phpMyAdmin and running it there.
All necessary tables will be generated and filled with some initial data. 

# DB credentials
You need to provide your own db credentials file. This file should be placed in the `src/database` folder.

An example of how the file structure would look like:
```
├── public
├── src
│   └── database
│       └── dbCredentials.php
├── README.md
├── initDB.sql
```
The format of the file is as follows:
```phpregexp
<?php
const DB_HOST = '<your host, could be localhost or 127.0.0.0>';
const DB_NAME = '<name of the database, if you ran the initDB.sql file the db's name is "db_project"';
const DB_USER = '<your db username>';
const DB_PWD =  '<your db password>';
```
Thus, if you want to (although it is not recommended), you could use the root user (given that you have not changed any settings regarding the login of the root user), an example of how this would look like:
````phpregexp
<?php
const DB_HOST = '127.0.0.1';
const DB_NAME = 'db_project';
const DB_USER = 'root';
const DB_PWD = '';
````
# Setup

This is a composer based project, so you need to have composer installed. You can find it [here](https://getcomposer.org).

Then you can run the following command to install all dependencies:
```bash
composer install
```

Run the development server on `localhost:8080` with:
```bash
composer start
```

You can also run tests with the following command:
```bash
composer test
```

# Deployment

> TODO

# Milestones:

### Milestone 1

* You will find the conceptual and logical model in the wiki. They are located in their respective folders.
* The different endpoints are also defined in the `Endpoints` part of the wiki 
* The physical part of the implementation of the database is located in the `initDB.sql` file.

### Milestone 2

* Conceptual, Logical and Physical model are updated according to feedback. 
* The test specs are located in their respective folder in the wiki, under `Tests/API tests` and `Tests/Unit tests`. 
