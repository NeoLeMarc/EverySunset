create table webcams (
    id int auto_increment,
    lon decimal(6,3),
    lat decimal(6,3),
    title varchar(100),
    url varchar(255),
    status integer,
    primary key(id)
) engine = InnoDB;
