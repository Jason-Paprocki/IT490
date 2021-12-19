create table if not exists `Forum` (
`fname` varchar(20) not null,
`lname` varchar(20) not null,
`post_id` varchar(13) not NULL,
`message_id` varchar(13),
`title` varchar(100) not null,
`message` varchar(1000) not null,
PRIMARY KEY (`post_id`)
) CHARACTER SET utf8 COLLATE utf8_general_ci