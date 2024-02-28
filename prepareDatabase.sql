CREATE TABLE `user` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    `created` int(11) NOT NULL,
    `updated` int(11) NOT NULL,
    `is_deleted` int(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `session` (
   `token` varchar(255) NOT NULL,
   `uid` int(11) NOT NULL,
   `expires` int(11) NOT NULL,
   `created` int(11) NOT NULL,
   `updated` int(11) NOT NULL,
   `is_deleted` int(11) NOT NULL DEFAULT 0,
   PRIMARY KEY (`token`),
   KEY `uid` (`uid`),
   CONSTRAINT `session_user_fk` FOREIGN KEY (`uid`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
