CREATE DATABASE `final-project`;
CREATE TABLE `blog-posts`(
	`id`	int(11) NOT NULL AUTO_INCREMENT,
    `title`	varchar(200) NOT NULL,
    `content` text NOT NULL,
    `description` varchar(200) NOT NULL,
    primary key (id)
);
