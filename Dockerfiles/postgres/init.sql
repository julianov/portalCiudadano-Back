-- Init database for postgres, create user and database

-- Create user
CREATE USER postgres WITH PASSWORD 'postgres';

-- Create database with id and name
CREATE DATABASE portalCiudadano WITH OWNER postgres;

GRANT ALL PRIVILEGES ON DATABASE portalCiudadano TO postgres;

-- Create table to docker database with id and name
\c docker
CREATE TABLE test (id SERIAL PRIMARY KEY, name VARCHAR(255));

-- Insert data to docker table
INSERT INTO test (name) VALUES ('docker 1');
INSERT INTO test (name) VALUES ('docker 2');



