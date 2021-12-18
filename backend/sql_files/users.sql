--  added highlight_token column in the table

CREATE TABLE if not exists `users` (
  `id` varchar(32) NOT NULL,
  `email` varchar(100) NOT NULL,
  `cookie` varchar(13) DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  `fname` varchar(20) NOT NULL,
  `lname` varchar(20) NOT NULL,
  `highlight_token` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
