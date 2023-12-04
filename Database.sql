create table "user" (
	id serial primary key,
	name varchar(100) not null,
	last_name varchar(100) not null,
	email varchar(150) not null unique,
	"password" varchar(255) not null,
	role_id int
);


create table "role" (
	id serial primary key,
	name varchar(20) not null
);


alter table "user" add constraint fk_user_rol foreign key (role_id) references role(id);

insert into "role"(name) values ('admin'), ('traninee'), ('trainer');