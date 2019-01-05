CREATE TABLE pms.users
(
    id varchar(36) DEFAULT uuid() PRIMARY KEY NOT NULL,
    name varchar(255) NOT NULL,
    surname varchar(255) NOT NULL,
    email varchar(255) NOT NULL,
    password text NOT NULL
);
CREATE UNIQUE INDEX users_id_uindex ON pms.users (id);
CREATE UNIQUE INDEX users_email_uindex ON pms.users (email);