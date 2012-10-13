
--
-- Struttura della tabella `blog`
--

CREATE TABLE IF NOT EXISTS `blog` (
  `blog_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `theme` varchar(64) NOT NULL DEFAULT 'default',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`blog_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8  ;

-- --------------------------------------------------------

--
-- Struttura della tabella `blog_author`
--

CREATE TABLE IF NOT EXISTS `blog_author` (
  `blog_author_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`blog_author_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `blog_category`
--

CREATE TABLE IF NOT EXISTS `blog_category` (
  `blog_category_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `blog_id` int(11) NOT NULL,
  PRIMARY KEY (`blog_category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `blog_post`
--

CREATE TABLE IF NOT EXISTS `blog_post` (
  `blog_post_id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_id` int(11) NOT NULL,
  `blog_author_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `pub_date` datetime NOT NULL,
  `summary` text NOT NULL,
  `content` mediumtext NOT NULL,
  `blog_category_id` int(11) NOT NULL,
  `status` enum('published','draft') NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`blog_post_id`),
  KEY `status` (`status`),
  KEY `blog_id` (`blog_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `blog_post_image`
--

CREATE TABLE IF NOT EXISTS `blog_post_image` (
  `blog_post_image_id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_post_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  PRIMARY KEY (`blog_post_image_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `blog_post_tag`
--

CREATE TABLE IF NOT EXISTS `blog_post_tag` (
  `blog_post_id` int(11) NOT NULL,
  `blog_tag_id` int(11) NOT NULL,
  PRIMARY KEY (`blog_post_id`,`blog_tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `blog_tag`
--

CREATE TABLE IF NOT EXISTS `blog_tag` (
  `blog_tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(64) NOT NULL,
  `url_tag` varchar(64) NOT NULL,
  PRIMARY KEY (`blog_tag_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
