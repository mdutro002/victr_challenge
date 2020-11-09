CREATE DATABASE vumc_db;

use vumc_db;

CREATE TABLE php_repos(	
  id integer PRIMARY KEY NOT NULL AUTO_INCREMENT, 
  repo_id varchar(256),
	name varchar(256),
  created_date varchar(256),
  last_push varchar(256),
	description varchar(256),
	stars integer 
);