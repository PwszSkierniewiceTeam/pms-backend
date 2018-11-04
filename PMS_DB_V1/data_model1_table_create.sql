CREATE TABLE public.e_projects (
    id_project integer NOT NULL,
    name varchar(30) NOT NULL,
    description varchar(100) NOT NULL,
    customer varchar(0) NOT NULL,
    start_date date NOT NULL,
    end_date date NOT NULL,
    price integer NOT NULL,
    time integer NOT NULL,
    comment varchar(250) NOT NULL,
    column2  NOT NULL,
    PRIMARY KEY (id_project)
);

CREATE INDEX i_id_project ON public.e_project
    (id_project);

CREATE TABLE public.e_tasks (
    id_task integer NOT NULL,
    id_project integer NOT NULL,
    task_type integer NOT NULL,
    name varchar(40) NOT NULL,
    start_date date NOT NULL,
    end_date date NOT NULL,
    status varchar(11) NOT NULL,
    PRIMARY KEY (id_task)
);

CREATE INDEX i_id_task ON public.e_tasks
    (id_task);

CREATE TABLE public.e_users (
    id_user integer NOT NULL,
    user_role varchar(10) NOT NULL,
    given_name varchar(20) NOT NULL,
    last_name varchar(30) NOT NULL,
    city varchar(30) NOT NULL,
    street varchar(30) NOT NULL,
    no_house integer NOT NULL,
    no_phone varchar(15) NOT NULL,
    e_mail varchar(30) NOT NULL,
    comment varchar(250) NOT NULL,
    PRIMARY KEY (id_user)
);

CREATE INDEX i_id_user ON public.e_users
    (id_user);

CREATE TABLE public.ew_devs_tasks (
    id_developer integer NOT NULL,
    id_task integer NOT NULL,
    data date NOT NULL
);



COMMENT ON COLUMN public.e_tasks.status
IS 'NOWE 
W_TRAKCIE
TESTY
DOSTARCZONE';

