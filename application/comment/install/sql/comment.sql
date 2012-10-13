
--
-- Struttura della tabella `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `comment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `item` varchar(100) NOT NULL,
  `item_id` int(11) unsigned NOT NULL,
  `content` text NOT NULL,
  `parent_comment_id` int(11) unsigned NOT NULL DEFAULT '0',
  `author_name` varchar(100) NOT NULL,
  `author_email` varchar(100) NOT NULL,
  `author_url` varchar(150) NOT NULL,
  `author_ip` varchar(15) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('pending','accepted','rejected') NOT NULL DEFAULT 'pending',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`comment_id`),
  KEY `item` (`item`,`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;
