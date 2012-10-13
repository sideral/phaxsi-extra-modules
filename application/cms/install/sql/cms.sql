
--
-- Struttura della tabella `cms_language`
--

CREATE TABLE IF NOT EXISTS `cms_language` (
  `language_id` char(5) NOT NULL,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `cms_page`
--

CREATE TABLE IF NOT EXISTS `cms_page` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_page_id` int(11) NOT NULL DEFAULT '0',
  `reference_name` varchar(255) NOT NULL,
  `template_id` int(11) NOT NULL,
  `in_menu` tinyint(1) NOT NULL DEFAULT '1',
  `editable` tinyint(1) NOT NULL DEFAULT '1',
  `sort_value` double NOT NULL,
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `cms_page_item`
--

CREATE TABLE IF NOT EXISTS `cms_page_item` (
  `page_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  PRIMARY KEY (`page_item_id`),
  KEY `page_id` (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `cms_page_item_picture`
--

CREATE TABLE IF NOT EXISTS `cms_page_item_picture` (
  `page_item_picture_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_item_id` int(11) NOT NULL,
  `filename` varchar(150) NOT NULL,
  `sort_value` double(15,15) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`page_item_picture_id`),
  KEY `page_id` (`page_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `cms_page_item_text`
--

CREATE TABLE IF NOT EXISTS `cms_page_item_text` (
  `page_item_text_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_item_id` int(11) NOT NULL,
  `language_id` char(5) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`page_item_text_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `cms_page_picture`
--

CREATE TABLE IF NOT EXISTS `cms_page_picture` (
  `page_picture_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `filename` varchar(150) NOT NULL,
  `sort_value` double NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`page_picture_id`),
  KEY `page_id` (`page_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `cms_page_text`
--

CREATE TABLE IF NOT EXISTS `cms_page_text` (
  `page_text_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `language_id` char(5) NOT NULL,
  `menu_entry` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `url_entry` varchar(50) NOT NULL,
  `meta_title` varchar(255) NOT NULL,
  `meta_description` varchar(255) NOT NULL,
  `meta_keywords` varchar(255) NOT NULL,
  PRIMARY KEY (`page_text_id`),
  UNIQUE KEY `section_id` (`page_id`,`language_id`),
  KEY `url_entry` (`url_entry`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `cms_template`
--

CREATE TABLE IF NOT EXISTS `cms_template` (
  `template_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `path` varchar(100) NOT NULL,
  `listed` tinyint(1) NOT NULL DEFAULT '1',
  `has_text` tinyint(1) NOT NULL DEFAULT '0',
  `has_meta` tinyint(1) NOT NULL DEFAULT '0',
  `has_pictures` tinyint(1) NOT NULL DEFAULT '0',
  `has_list` tinyint(1) NOT NULL DEFAULT '0',
  `has_menu` tinyint(1) NOT NULL DEFAULT '0',
  `action` varchar(50) NOT NULL,
  PRIMARY KEY (`template_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;
