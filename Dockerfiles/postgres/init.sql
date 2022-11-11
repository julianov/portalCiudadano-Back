-- Init database for postgres, create user and database

-- Create user
CREATE USER docker WITH PASSWORD 'docker';

-- Create database with id and name
CREATE DATABASE docker WITH OWNER docker;

GRANT ALL PRIVILEGES ON DATABASE docker TO docker;

-- Create table to docker database with id and name
\c docker
CREATE TABLE docker (id SERIAL PRIMARY KEY, name VARCHAR(255));

-- Insert data to docker table
INSERT INTO docker (name) VALUES ('docker 1');
INSERT INTO docker (name) VALUES ('docker 2');

-- Grant all privileges on database to user





