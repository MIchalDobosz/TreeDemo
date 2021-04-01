drop table if exists struct; 

create table struct (
	
	id serial primary key,
	name varchar not null,
	parent_id int references struct(id)
);


insert into struct (name)
values ('Home');

insert into struct (name, parent_id)
values ('Users', 1);

insert into struct (name, parent_id)
values ('Global', 1);

insert into struct (name, parent_id)
values ('John', 2);

insert into struct (name, parent_id)
values ('Annie', 2);