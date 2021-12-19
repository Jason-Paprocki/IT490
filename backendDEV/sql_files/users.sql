create table if not exists `Users` (
`id` varchar(32) not null,
`email` varchar(100) not null unique,
`cookie` varchar(13),
`password` varchar(100) not null,
`fname` varchar(20) not null,
`lname` varchar(20) not null,
`loginTime` varchar(20) not null,
PRIMARY KEY (`id`)
) CHARACTER SET utf8 COLLATE utf8_general_ci