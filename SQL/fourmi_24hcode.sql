CREATE TABLE `jwt_token` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
ALTER TABLE `jwt_token`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `jwt_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

CREATE TABLE `cicada_memory` (
  `id` int(11) NOT NULL,
  `track_id` varchar(100) NOT NULL,
  `done` BIT NOT NULL DEFAULT FALSE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
ALTER TABLE `cicada_memory`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `cicada_memory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;