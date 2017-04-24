CREATE TABLE IF NOT EXISTS `albums` (
  `album_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `date_created` timestamp DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`album_id`)
);


CREATE TABLE IF NOT EXISTS `images` (
  `image_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `caption` text COLLATE latin1_general_ci NOT NULL,
  `file_path`  varchar(512)  NOT NULL,
  `credit` varchar(512) NOT NULL,
  PRIMARY KEY (`image_id`)
);

CREATE TABLE IF NOT EXISTS `display` (
	`album_id` int(11) NOT NULL,
	`image_id` int(11) NOT NULL,
	PRIMARY KEY(`album_id`,`image_id`),
	FOREIGN KEY (`album_id`) REFERENCES 'albums'(`album_id`),
	FOREIGN KEY (`image_id`) REFERENCES 'albums'(`image_id`),
）;



INSERT INTO `albums` (`album_id`, `title`, `date_created`,`date_modified`) VALUES
(1, 'Action','2017-03-02','2017-03-02');
(2,'Drama','2017-03-10','2017-03-10');
INSERT INTO `images` VALUES
(1,'Beauty and the Beast','An adaptation of the Disney fairy tale about a monstrous-looking prince and a young woman who fall in love.','images/Beauty_and_Beast.jpg','https://images-na.ssl-images-amazon.com/images/M/MV5BMTUwNjUxMTM4NV5BMl5BanBnXkFtZTgwODExMDQzMTI@._V1_SY1000_CR0,0,674,1000_AL_.jpg'),
(2,'X-Men: Apocalypse',"After the re-emergence of the world's first mutant, world-destroyer Apocalypse, the X-Men must unite to defeat his extinction level plan.",'images/X-man.jpg','https://images-na.ssl-images-amazon.com/images/M/MV5BMjU1ODM1MzYxN15BMl5BanBnXkFtZTgwOTA4NDE2ODE@._V1_SY1000_CR0,0,676,1000_AL_.jpg'),
(3,'The Great Wall','European mercenaries searching for black powder become embroiled in the defense of the Great Wall of China against a horde of monstrous creatures.','images/The_Great_Wall.jpg','https://images-na.ssl-images-amazon.com/images/M/MV5BMjA3MjAzOTQxNF5BMl5BanBnXkFtZTgwOTc5OTY1OTE@._V1_SY1000_CR0,0,631,1000_AL_.jpg')
(4,'Moonlight','A chronicle of the childhood, adolescence and burgeoning adulthood of a young black man growing up in a rough neighborhood of Miami.','images/Moonlight.jpg','https://images-na.ssl-images-amazon.com/images/M/MV5BNzQxNTIyODAxMV5BMl5BanBnXkFtZTgwNzQyMDA3OTE@._V1_SY1000_CR0,0,674,1000_AL_.jpg'),
(5,'They Call Me Jeeg','Enzo, a lonely and misanthropic small time crook, uses the superpowers gained after falling in the Tiber river to chase down a crazy gangster called "The gypsy".','images/Theycallmejeeg.jpg','source:https://images-na.ssl-images-amazon.com/images/M/MV5BMTk1OTNjNDEtN2M3Zi00MDQ1LWJjZTktN2Y4NGFhNzZiYzg5XkEyXkFqcGdeQXVyNTA0OTU0OTQ@._V1_SY1000_SX700_AL_.jpg'),
(6,'Logan',"In the near future, a weary Logan cares for an ailing Professor X somewhere on the Mexican border. However, Logan's attempts to hide from the world and his legacy are upended when a young mutant arrives, pursued by dark forces.",'images/Logan.jpg','source:https://images-na.ssl-images-amazon.com/images/M/MV5BMjI1MjkzMjczMV5BMl5BanBnXkFtZTgwNDk4NjYyMTI@._V1_SY1000_CR0,0,676,1000_AL_.jpg'),
(7,'La La Land',"A jazz pianist falls for an aspiring actress in Los Angeles.",'images/Lalaland.jpg','source:https://images-na.ssl-images-amazon.com/images/M/MV5BMzUzNDM2NzM2MV5BMl5BanBnXkFtZTgwNTM3NTg4OTE@._V1_SY1000_SX675_AL_.jpg'),
(8,'Caption Fantastic',"the forests of the Pacific Northwest, a father devoted to raising his six kids with a rigorous physical and intellectual education is forced to leave his paradise and enter the world, challenging his idea of what it means to be a parent.",'images/Captainfantastic.jpg','source:https://images-na.ssl-images-amazon.com/images/M/MV5BMjE5OTM0OTY5NF5BMl5BanBnXkFtZTgwMDcxOTQ3ODE@._V1_SY1000_CR0,0,674,1000_AL_.jpg');
INSERT INTO `display` VALUES
(1,1),
(1,2),
(1,3),
(2,4),
(1,5),
(1,6),
(2,1),
(2,6),
(2,7),
(2,8),
(3,5),
(3,7),
(3,8);

INSERT INTO `albums` (`title`) VALUES
(`Comedy`)
