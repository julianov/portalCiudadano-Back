create user desaportal
      identified by desaportal
      default tablespace users
      temporary tablespace temp
      quota unlimited on users;

grant create session, create table to desaportal;
grant create view, create procedure, create sequence to desaportal;

-- create table "test" for desaportal
create table test (id number, name varchar2(20));

-- insert data into "test" for desaportal
insert into test values (1, 'test1');
insert into test values (2, 'test2');
insert into test values (3, 'test3');
