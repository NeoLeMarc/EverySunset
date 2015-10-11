create table webcams (
    id int auto_increment,
    lon decimal(6,3),
    lat decimal(6,3),
    title varchar(100),
    url varchar(255),
    active boolean not null default 1,
    primary key(id)
) engine = InnoDB charset=utf-8;

create table status (
    webcam_id int,
    http_status int,
    sunrise time,
    sunset time,
    comment varchar(255),
    parsedate timestamp,
    foreign key(webcam_id) references webcams(id),
    primary key(webcam_id)
) engine = InnoDB;
