CREATE DATABASE `final-project`;
CREATE TABLE `blog-posts`(
	`id`	int(11) NOT NULL AUTO_INCREMENT,
    `title`	varchar(200) NOT NULL,
    `content` text NOT NULL,
    `description` varchar(200) NOT NULL,
    `date`        varchar(10) NOT NULL,
    primary key (id)
);

CREATE TABLE `users`
(
    `id`        int(11) NOT NULL AUTO_INCREMENT,
    `username`      varchar(80) NOT NULL,
    `password`      varchar(80) NOT NULL,
    `sessionExpiration` DATETIME DEFAULT NULL,
    primary key (`id`)
);
--due to nature of website, there is only one username and password, which was directly inserted into database
--and replaced with dummy variables, :username and :password
insert into users (username, password)
values (:password, :username);