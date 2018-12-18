CREATE TABLE pms.projects
(
  id integer PRIMARY KEY NOT NULL,
  name varchar(255) NOT NULL,
  description varchar(255) NOT NULL,
  startDate datetime NOT NULL,
  endDate datetime NOT NULL,
  admin varchar(36)
);
CREATE UNIQUE INDEX projects_id_uindex ON pms.projects (id);