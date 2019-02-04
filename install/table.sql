
--
-- Структура таблицы `ads`
--

CREATE TABLE IF NOT EXISTS `ads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kto` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `link` varchar(255) NOT NULL,
  `local` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `time_end` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `ban`
--

CREATE TABLE IF NOT EXISTS `ban` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kto` int(11) NOT NULL,
  `komy` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `time_end` int(11) NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `dialogs`
--

CREATE TABLE IF NOT EXISTS `dialogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kto` int(11) NOT NULL,
  `komy` int(11) NOT NULL,
  `time_last` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `dialogs_message`
--

CREATE TABLE IF NOT EXISTS `dialogs_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kto` int(11) NOT NULL,
  `komy` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `message` text NOT NULL,
  `readln` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `dialogs_file`
--

CREATE TABLE IF NOT EXISTS `dialogs_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kto` int(11) NOT NULL,
  `komy` int(11) NOT NULL,
  `mess_id` int(11) NOT NULL,
  `name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `forum_file`
--

CREATE TABLE IF NOT EXISTS `forum_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kto` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `thema` int(11) NOT NULL,
  `name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `forum_message`
--

CREATE TABLE IF NOT EXISTS `forum_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `razdel` int(11) NOT NULL,
  `section` int(11) NOT NULL,
  `topic` int(11) NOT NULL,
  `kto` int(11) NOT NULL,
  `message` varchar(1024) NOT NULL,
  `user_quote` varchar(255) NOT NULL DEFAULT '',
  `quote` varchar(1024) NOT NULL DEFAULT '',
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `forum_razdel`
--

CREATE TABLE IF NOT EXISTS `forum_razdel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `pos` int(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `forum_section`
--

CREATE TABLE IF NOT EXISTS `forum_section` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `razdel` int(11) NOT NULL,
  `pos` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `forum_topic`
--

CREATE TABLE IF NOT EXISTS `forum_topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `razdel` int(11) NOT NULL,
  `section` int(11) NOT NULL,
  `kto` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `last_message_time` int(11) NOT NULL,
  `is_top_topic` int(11) NOT NULL DEFAULT '0',
  `is_close_topic` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `guests`
--

CREATE TABLE IF NOT EXISTS `guests` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `ip` varchar(20) NOT NULL,
  `browser` text NOT NULL,
  `time` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Структура таблицы `journal`
--

CREATE TABLE IF NOT EXISTS `journal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kto` int(11) NOT NULL,
  `komy` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `message` text NOT NULL,
  `url` varchar(255) NOT NULL,
  `readln` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `lib_category`
--

CREATE TABLE IF NOT EXISTS `lib_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `lib_comments`
--

CREATE TABLE IF NOT EXISTS `lib_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kto` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `message` varchar(1024) NOT NULL,
  `lib_r` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `lib_r`
--

CREATE TABLE IF NOT EXISTS `lib_r` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kto` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `message` text NOT NULL,
  `txt` varchar(255) NOT NULL,
  `look` int(11) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `chat`
--

CREATE TABLE IF NOT EXISTS `chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kto` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `admin_chat`
--

CREATE TABLE IF NOT EXISTS `admin_chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kto` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `module`
--

CREATE TABLE IF NOT EXISTS `module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `open` int(11) NOT NULL,
  `counter` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kto` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `news_comments`
--

CREATE TABLE IF NOT EXISTS `news_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kto` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `message` varchar(1024) NOT NULL,
  `news` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(255) NOT NULL,
  `theme` varchar(255) NOT NULL,
  `num` int(11) NOT NULL DEFAULT '10',
  `close` int(11) NOT NULL DEFAULT '0',
  `keywords` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Дамп данных таблицы `settings`
--

INSERT INTO `settings` (`id`, `language`, `theme`, `num`, `close`, `keywords`, `description`) VALUES
(1, 'ru', 'default', 15, 1, 'NomiCMS', 'NomiCMS');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(32) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` int(11) NOT NULL DEFAULT '1',
  `name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL DEFAULT '',
  `sex` int(11) NOT NULL,
  `email` varchar(255) NOT NULL DEFAULT '',
  `tg` varchar(40) NOT NULL DEFAULT '',
  `money` int(11) NOT NULL DEFAULT '1000',
  `date_registration` int(11) NOT NULL,
  `date_last_entry` int(11) NOT NULL,
  `ava` varchar(255) NOT NULL DEFAULT 'no_ava.jpg',
  `country` varchar(255) NOT NULL DEFAULT '',
  `city` varchar(255) NOT NULL DEFAULT '',
  `about` varchar(255) NOT NULL DEFAULT '',
  `browser` varchar(255) NOT NULL DEFAULT '',
  `ip` varchar(20) NOT NULL DEFAULT '',
  `browser_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `user_settings`
--

CREATE TABLE IF NOT EXISTS `user_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kto` int(11) NOT NULL,
  `language` varchar(255) NOT NULL,
  `theme` varchar(255) NOT NULL,
  `num` int(11) NOT NULL DEFAULT '10',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `zc_category`
--

CREATE TABLE IF NOT EXISTS `zc_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `opis` text NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `zc_comments`
--

CREATE TABLE IF NOT EXISTS `zc_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kto` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `message` varchar(1024) NOT NULL,
  `zc_file` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `zc_file`
--

CREATE TABLE IF NOT EXISTS `zc_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kto` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `section` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `opis` text NOT NULL,
  `file` text NOT NULL,
  `screen` varchar(255) NOT NULL DEFAULT '',
  `time` int(11) NOT NULL,
  `down` int(11) NOT NULL DEFAULT '0',
  `pin` int(11) NOT NULL DEFAULT '0',
  `hide` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `zc_section`
--

CREATE TABLE IF NOT EXISTS `zc_section` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `whitelist` text NOT NULL,
  `max_size` int(11) NOT NULL,
  `hide_files` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

CREATE TABLE `friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kto` int(11) NOT NULL,
  `komy` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `blogs`
--

CREATE TABLE IF NOT EXISTS `blogs` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `kto` int(32) NOT NULL,
  `time` int(32) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `blog_comms`
--

CREATE TABLE IF NOT EXISTS `blog_comms` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `time` int(32) NOT NULL,
  `kto` int(32) NOT NULL,
  `blog_id` int(32) NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `wall`
--

CREATE TABLE IF NOT EXISTS `wall` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kto` int(11) NOT NULL,
  `komy` int(11) NOT NULL,
  `message` text NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

