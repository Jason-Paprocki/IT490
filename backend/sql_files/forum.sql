-- added the column user_id and make it foreign key with the id of users table


create table if not exists `Forum` (
`fname` varchar(20) not null,
`lname` varchar(20) not null,
`post_id` varchar(13) not NULL,
`message_id` varchar(13),
`title` varchar(100) not null,
`message` varchar(1000) not null,
PRIMARY KEY (`post_id`)
) CHARACTER SET utf8 COLLATE utf8_general_ci



ALTER TABLE `forum`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `forum`
  ADD CONSTRAINT `forum_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);