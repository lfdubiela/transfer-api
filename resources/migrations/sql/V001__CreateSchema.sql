-- apenas dominio de user;
-- CREATE TABLE user (
--     id int not null auto_increment primary key,
--     name varchar(255) not null,
--     document_type varchar(10) not null,
--     document_number varchar(30) not null unique,
--     email varchar(255) not null unique,
--     password varchar(32) not null
-- );

-- dominio de wallet;
CREATE TABLE user (
    id int not null auto_increment primary key,
    is_store boolean not null default false
);

CREATE TABLE wallet (
    id int not null primary key,
    balance decimal(8,2) not null default 0.00,
    foreign key (id) references user(id)
);



