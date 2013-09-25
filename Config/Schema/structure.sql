DROP TABLE IF EXISTS sheet_contents;
DROP TABLE IF EXISTS sheet_structures;

DROP TABLE IF EXISTS sheets;
DROP TABLE IF EXISTS contents;
DROP TABLE IF EXISTS structures;

CREATE TABLE `sheets`(
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`name` varchar(30) NOT NULL,
        `view_name` varchar(25) NOT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY (`name`)
)ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='This table represent the actual page, but that name is taken by the default cakephp pages controller. Better not to break convention.';

CREATE TABLE `contents`(
	`id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(40) NOT NULL ,
        `value` longtext NOT NULL,
        PRIMARY KEY (`id`)
)ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='block name in a view';

CREATE TABLE `structures`(
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL COMMENT 'or key',
        `value` varchar(255) DEFAULT NULL,
        ./* threaded behavior implementation */
        `parent_id` int(11) DEFAULT NULL COMMENT 'relation with itself',
        `lft` int(11) DEFAULT NULL,
        `rght` int(11) DEFAULT NULL,
        FOREIGN KEY (`parent_id`) REFERENCES `structures` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
        PRIMARY KEY (`id`)
)ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='structures like an array, but more extensive because keys with an array as value (with child), can still hold values. This table allows data structures but the values are limeted to 255 chars';

CREATE TABLE `sheet_contents`(
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `sheet_id` int(11) NOT NULL,
        `content_id` int(11) NOT NULL,
        PRIMARY KEY (`id`),
        FOREIGN KEY (`sheet_id`) REFERENCES `sheets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
        
)ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Link table for sheets and contents, allows multiple on multiple relations';


CREATE TABLE `sheet_structures`(
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `sheet_id` int(11) NOT NULL,
        `structure_id` int(11) NOT NULL,
        PRIMARY KEY (`id`),
        FOREIGN KEY (`sheet_id`) REFERENCES `sheets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (`structure_id`) REFERENCES `structures` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Link table for sheets and structures, allows multiple on multiple relations';
