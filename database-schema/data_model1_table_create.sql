CREATE TABLE public.eProjects (
    id varchar(36) NOT NULL,
    name varchar(30) NOT NULL,
    description varchar(100) NOT NULL,
    customer varchar(0) NOT NULL,
    startDate date NOT NULL,
    endDate date NOT NULL,
    price integer NOT NULL,
    time integer NOT NULL,
    comment varchar(250) NOT NULL,
    PRIMARY KEY (id)
);


CREATE TABLE public.eTasks (
    id varchar(36) NOT NULL,
    idProject varchar(36) NOT NULL,
    taskType integer NOT NULL,
    name varchar(40) NOT NULL,
    startDate date NOT NULL,
    endDate date NOT NULL,
    status varchar(11) NOT NULL,
    PRIMARY KEY (id)
);

CREATE INDEX iTasks ON public.eTasks
    (idProject);



CREATE TABLE public.eUsers (
    id varchar(36) NOT NULL,
    userRole varchar(0) NOT NULL,
    givenName varchar(20) NOT NULL,
    lastName varchar(30) NOT NULL,
    city varchar(30) NOT NULL,
    street varchar(30) NOT NULL,
    noHouse integer NOT NULL,
    noPhone varchar(15) NOT NULL,
    email varchar(30) NOT NULL,
    comment varchar(250) NOT NULL,
    PRIMARY KEY (id)
);


CREATE TABLE public.eUsersProjects (
    id varchar(36) NOT NULL,
    idProject varchar(36) NOT NULL,
    role varchar(20) NOT NULL,
    date date NOT NULL
);

CREATE INDEX  ON public.eUsersProjects
    (id);
	
CREATE INDEX ON public.eUsersProjects
    (idProject);


CREATE TABLE public.eDevsTasks (
    idTask varchar(36) NOT NULL,
    idUser varchar(36) NOT NULL,
    date  NOT NULL
);

CREATE INDEX ON public.eDevsTasks
    (idTask);
	
CREATE INDEX ON public.eDevsTasks
    (idUser);

