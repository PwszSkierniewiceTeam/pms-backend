CREATE TABLE pms.users
(
    id varchar(36) DEFAULT uuid() PRIMARY KEY NOT NULL,
    name varchar(255) NOT NULL,
    surname varchar(255) NOT NULL,
    email varchar(255) NOT NULL,
    password text NOT NULL
);
CREATE UNIQUE INDEX UIX_userId ON pms.users (id);
CREATE UNIQUE INDEX UIX_userEmail ON pms.users (email);

CREATE TABLE pms.Projects (
    id varchar(36) DEFAULT uuid() NOT NULL,
    name varchar(30) NOT NULL,
    description varchar(255) NOT NULL,
    startDate date NOT NULL,
    endDate date NOT NULL,
    PRIMARY KEY (id)
);
CREATE UNIQUE INDEX UIX_projectId ON pms.Projects(id);

CREATE TABLE pms.UsersProjects (
    projectId varchar(36) NOT NULL,
    userId varchar(36) NOT NULL,
    role varchar(20) NOT NULL,
    CONSTRAINT PK_UsersProjects PRIMARY KEY (projectId, userId)
);
CREATE INDEX IX_userId ON pms.UsersProjects(userId);
CREATE INDEX IX_projectId ON pms.UsersProjects(projectId);

ALTER TABLE pms.UsersProjects ADD CONSTRAINT FK_UsersProjects_Users FOREIGN KEY (userId) REFERENCES pms.Users(id);
ALTER TABLE pms.UsersProjects ADD CONSTRAINT FK_UsersProjects_Projects FOREIGN KEY (projectId) REFERENCES pms.Projects(id);


CREATE TABLE pms.Tasks (
    id varchar(36) DEFAULT uuid() NOT NULL,
    name varchar(30) NOT NULL,
    projectId varchar(36) NOT NULL,
    description varchar(255) NOT NULL,
    type varchar(30) NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (projectId) REFERENCES Projects(id)
);
CREATE UNIQUE INDEX UIX_taskId ON pms.Tasks(id);
CREATE INDEX IX_projectId ON pms.Tasks(projectId);

CREATE TABLE pms.UsersTasks (
    taskId varchar(36) NOT NULL,
    userId varchar(36) NOT NULL,
    role varchar(20) NOT NULL,
    CONSTRAINT PK_UsersTasks PRIMARY KEY (userId, taskId)
);
CREATE INDEX IX_userId ON pms.UsersTasks(userId);
CREATE INDEX IX_taskId ON pms.UsersTasks(taskId);

ALTER TABLE pms.UsersTasks ADD CONSTRAINT FK_UsersTasks_Users FOREIGN KEY (userId) REFERENCES pms.Users(id);
ALTER TABLE pms.UsersTasks ADD CONSTRAINT FK_UsersTasks_Projects FOREIGN KEY (taskId) REFERENCES pms.Tasks(id);
