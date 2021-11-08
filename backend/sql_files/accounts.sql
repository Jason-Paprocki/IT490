create table if not exists `Accounts` (
`pname` varchar(100) not null,
`species` varchar(100),
`pic` varchar(100) not null,
`zip` varchar(100) not null,
PRIMARY KEY (`zip`)
) CHARACTER SET utf8 COLLATE utf8_general_ci
