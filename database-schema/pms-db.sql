create table user
(
  id       varchar(36)  not null
    primary key,
  name     varchar(100) not null,
  surname  varchar(100) not null,
  password text         not null,
  email    varchar(255) not null,
  constraint user_email_uindex
  unique (email)
);
