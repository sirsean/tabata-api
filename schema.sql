drop table if exists users;
drop table if exists history;

create table users (
    id int(11) not null auto_increment,
    email varchar(255) not null,
    password varchar(255) not null,
    primary key (id)
) engine=MyISAM default charset=utf8;

create table history (
    id int(11) not null auto_increment,
    userId int(11) not null,
    performedAt timestamp not null default NOW(),
    sprintSeconds int(11) not null,
    restSeconds int(11) not null,
    numSprints int(11) not null,
    timezone int(11) not null,
    primary key (id),
    index (userId)
) engine=MyISAM default charset=utf8;

