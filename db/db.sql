

CREATE TABLE IF NOT EXISTS `cats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tok` int(11) NOT NULL,
  `url` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `node` int(11) DEFAULT NULL,
  `dsc` text,
  `badge` int(5) DEFAULT NULL,
  `badgecolor` int(5) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tok` int(11) NOT NULL,
  `url` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `user` int(8) DEFAULT NULL,
  `note` text,
  `type` int(5) DEFAULT NULL,
  `cid` int(11) NOT NULL,
  `sticked` tinyint(1) DEFAULT NULL,
  `badge` varchar(255) NOT NULL DEFAULT '',
  `badgecolor` varchar(6) NOT NULL DEFAULT '',
  `screen` tinyint(1) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `tag2item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iid` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tok` int(11) NOT NULL,
  `url` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `label` varchar(255) NOT NULL DEFAULT '',
  `dsc` text,
  `node` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;



CREATE VIEW catgroup AS
    SELECT
        cats.name AS cat,
        cats.tok AS tok,
        cats.url AS url,
        count(*) AS itemcount
    FROM cats
    JOIN items ON
        items.cid=cats.tok
    GROUP BY
	cats.name
    ORDER BY itemcount DESC;
 

CREATE VIEW taggroup AS
    SELECT
        tags.label AS tag,
        tags.tok AS tok,
        tags.url AS url,
        count(*) AS tagcount
    FROM tags
    JOIN tag2item ON
        tag2item.tid = tags.id
    GROUP BY
	tags.label
    ORDER BY tagcount DESC;
 

CREATE VIEW itemstag AS
    SELECT
        items.title AS title,
        items.note AS note,
        items.tok AS tok,
        items.url AS url,
	tag2item.tid AS tagid,
	tags.tok AS tagtok,
	tags.label AS label
    FROM items
    JOIN tag2item ON
        items.id=tag2item.iid
    JOIN tags ON
        tags.id=tag2item.tid;


CREATE VIEW taglist AS
    SELECT
        tags.label,
	tags.tok, 
	tags.url, 
	items.tok as itok,
	items.cid as ctok
    FROM tags
    JOIN tag2item ON 
	tag2item.tid = tags.id
    JOIN items ON 
	tag2item.iid = items.id
    ORDER BY items.tok;




