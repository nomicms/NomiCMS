
INSERT INTO `blogs` (`id`, `text`, `kto`, `time`, `name`) VALUES
(1, 'Каждый из нас понимает очевидную вещь: высококачественный прототип будущего проекта выявляет срочную потребность прогресса профессионального сообщества. Имеется спорная точка зрения, гласящая примерно следующее: независимые государства описаны максимально подробно.\r\n\r\nДля современного мира понимание сути ресурсосберегающих технологий, а также свежий взгляд на привычные вещи - безусловно открывает новые горизонты для как самодостаточных, так и внешне зависимых концептуальных решений. Учитывая ключевые сценарии поведения, сложившаяся структура организации однозначно определяет каждого участника как способного принимать собственные решения касаемо распределения внутренних резервов и ресурсов. Кстати, интерактивные прототипы, которые представляют собой яркий пример континентально-европейского типа политической культуры, будут ассоциативно распределены по отраслям. Следует отметить, что синтетическое тестирование предоставляет широкие возможности для модели развития.\r\n\r\nСледует отметить, что дальнейшее развитие различных форм деятельности однозначно определяет каждого участника как способного принимать собственные решения касаемо экономической целесообразности принимаемых решений. Банальные, но неопровержимые выводы, а также сделанные на базе интернет-аналитики выводы неоднозначны и будут представлены в исключительно положительном свете. Вот вам яркий пример современных тенденций - повышение уровня гражданского сознания не оставляет шанса для экспериментов, поражающих по своей масштабности и грандиозности. Идейные соображения высшего порядка, а также постоянный количественный рост и сфера нашей активности в значительной степени обусловливает важность распределения внутренних резервов и ресурсов. Кстати, стремящиеся вытеснить традиционное производство, нанотехнологии, которые представляют собой яркий пример континентально-европейского типа политической культуры, будут разоблачены.', 1, 1539792874, 'Банальные, но неопровержимые выводы'),
(2, 'Товарищи! рамки и место обучения кадров в значительной степени обуславливает создание дальнейших направлений развития. Разнообразный и богатый опыт постоянное информационно-пропагандистское обеспечение нашей деятельности в значительной степени обуславливает создание соответствующий условий активизации. Идейные соображения высшего порядка, а также консультация с широким активом в значительной степени обуславливает создание форм развития. Не следует, однако забывать, что постоянное информационно-пропагандистское обеспечение нашей деятельности требуют от нас анализа модели развития.\r\n\r\nТаким образом постоянное информационно-пропагандистское обеспечение нашей деятельности требуют определения и уточнения системы обучения кадров, соответствует насущным потребностям. Не следует, однако забывать, что рамки и место обучения кадров требуют от нас анализа позиций, занимаемых участниками в отношении поставленных задач. Повседневная практика показывает, что рамки и место обучения кадров позволяет выполнять важные задания по разработке направлений прогрессивного развития. Не следует, однако забывать, что начало повседневной работы по формированию позиции способствует подготовки и реализации существенных финансовых и административных условий. Таким образом консультация с широким активом представляет собой интересный эксперимент проверки существенных финансовых и административных условий. Задача организации, в особенности же дальнейшее развитие различных форм деятельности влечет за собой процесс внедрения и модернизации системы обучения кадров, соответствует насущным потребностям.', 1, 1539792972, 'Идейные соображения высшего порядка');

-- --------------------------------------------------------

INSERT INTO `dialogs` (`id`, `kto`, `komy`, `time_last`) VALUES
(1, 1, 2, 1539795043),
(2, 2, 1, 1539795043);

-- --------------------------------------------------------

INSERT INTO `dialogs_message` (`id`, `kto`, `komy`, `time`, `message`, `readln`) VALUES
(1, 1, 2, 1539794947, 'Повседневная практика показывает, что рамки и место обучения кадров позволяет выполнять важные задания по разработке направлений прогрессивного развития', 1),
(2, 2, 1, 1539794966, 'Значимость этих проблем настолько очевидна, что глубокий уровень погружения выявляет срочную потребность благоприятных перспектив :)', 1),
(3, 1, 2, 1539795043, 'Товарищи! [b]новая модель[/b] организационной деятельности представляет собой интересный эксперимент проверки позиций, занимаемых участниками в отношении поставленных задач.', 1);

-- --------------------------------------------------------

INSERT INTO `forum_file` (`id`, `kto`, `post_id`, `thema`, `name`) VALUES
(1, 2, 11, 1, '000_NOMICMS_000.rar');

-- --------------------------------------------------------

INSERT INTO `forum_message` (`id`, `razdel`, `section`, `topic`, `kto`, `message`, `user_quote`, `quote`, `time`) VALUES
(1, 1, 1, 1, 1, 'Тема закреплена!', '', '', 1539790583),
(2, 2, 2, 3, 2, 'В целом, конечно, постоянный количественный рост и сфера нашей активности способствует повышению качества форм воздействия.', '', '', 1539790775),
(3, 2, 2, 3, 1, '[rep]User[/rep] Сложно сказать, почему элементы политического процесса неоднозначны и будут представлены в исключительно положительном свете. Банальные, но неопровержимые выводы, а также элементы политического процесса лишь добавляют фракционных разногласий и указаны как претенденты на роль ключевых факторов :bravo:', '', '', 1539790974),
(4, 2, 2, 3, 2, 'Не следует, однако, забывать, что синтетическое тестирование позволяет оценить значение первоочередных требований', 'admin', '[rep]User[/rep] Сложно сказать, почему элементы политического процесса неоднозначны и будут представлены в исключительно положительном свете. Банальные, но неопровержимые выводы, а также элементы политического процесса лишь добавляют фракционных разногласий и указаны как претенденты на роль ключевых факторов :bravo:', 1539791080),
(5, 2, 2, 3, 1, '[rep]User[/rep] хмм... :angry:', '', '', 1539795191),
(6, 2, 2, 4, 1, 'Тема закрыта!', '', '', 1539794298),
(7, 1, 1, 1, 2, 'Товарищи! рамки и место обучения кадров в значительной степени обуславливает создание дальнейших направлений развития!', '', '', 1539796336);

-- --------------------------------------------------------

INSERT INTO `forum_razdel` (`id`, `name`, `pos`) VALUES
(1, 'NomiCMS', 1),
(2, 'Общение', 2);

-- --------------------------------------------------------

INSERT INTO `forum_section` (`id`, `name`, `razdel`, `pos`) VALUES
(1, 'Обсуждение', 1, 1),
(2, 'Флуд / Оффтоп', 2, 1);

-- --------------------------------------------------------

INSERT INTO `forum_topic` (`id`, `razdel`, `section`, `kto`, `name`, `message`, `time`, `last_message_time`, `is_top_topic`, `is_close_topic`) VALUES
(1, 1, 1, 1, 'Топ тема: Баги / Ошибки', 'В данной теме пишем баги/ошибки которые вы нашли', 1539789901, 1539796336, 1, 0),
(2, 1, 1, 1, 'Предложения / Замечания', 'Тут пишем предложения или замечания по движку ;-)', 1539790195, 1539790195, 0, 0),
(3, 2, 2, 1, 'Свободное общение', 'Всем привет B-)', 1539790454, 1539795191, 0, 0),
(4, 2, 2, 1, 'Закрытая тема', 'действительно :(', 1539794293, 1539794293, 0, 1);

-- --------------------------------------------------------

INSERT INTO `friends` (`id`, `kto`, `komy`, `status`) VALUES
(1, 1, 2, 1),
(2, 2, 1, 1);

-- --------------------------------------------------------

INSERT INTO `journal` (`id`, `kto`, `komy`, `time`, `message`, `url`, `readln`) VALUES
(1, 1, 2, 1539790974, 'replay_forum||Свободное общение', '/forum/topic3', 1),
(2, 2, 1, 1539791080, 'replay_quote||Свободное общение', '/forum/topic3', 1),
(3, 1, 2, 1539792191, 'replay_forum||Свободное общение', '/forum/topic3', 1),
(4, 1, 2, 1539797071, 'replay_chat', '/chat/3', 1);

-- --------------------------------------------------------

INSERT INTO `lib_category` (`id`, `name`, `time`) VALUES
(1, 'Философия', 1539795214);

-- --------------------------------------------------------

INSERT INTO `lib_r` (`id`, `kto`, `category`, `name`, `message`, `txt`, `look`, `time`) VALUES
(1, 1, 1, 'Сторонники тоталитаризма', 'Также как глубокий уровень погружения однозначно фиксирует необходимость стандартных подходов. В целом, конечно, убежденность некоторых оппонентов является качественно новой ступенью существующих финансовых и административных условий. Но постоянное информационно-пропагандистское обеспечение нашей деятельности обеспечивает широкому кругу (специалистов) участие в формировании поставленных обществом задач.', '000_NOMICMS_000.rar', 21, 1539795290);

-- --------------------------------------------------------

INSERT INTO `chat` (`id`, `kto`, `time`, `message`) VALUES
(1, 1, 1539797002, 'Но понимание сути ресурсосберегающих технологий не дает нам иного выбора, кроме определения прогресса профессионального сообщества'),
(2, 2, 1539797031, 'Банальные, но неопровержимые выводы :@'),
(3, 1, 1539797071, '[rep]User[/rep] Элементы политического процесса, которые представляют собой яркий пример континентально-европейского типа политической культуры, будут заблокированы в рамках своих собственных рациональных ограничений'),
(4, 2, 1539797224, ':angry:');

-- --------------------------------------------------------

INSERT INTO `news` (`id`, `kto`, `time`, `name`, `message`) VALUES
(1, 1, 1539777224, 'NomiCMS v2.x', 'Основные изменения в этой версии:\r\n- см. на http://nomicms.ru');

-- --------------------------------------------------------

INSERT INTO `users` (`id`, `login`, `password`, `level`, `name`, `first_name`, `sex`, `email`, `tg`, `money`, `date_registration`, `date_last_entry`, `ava`, `country`, `city`, `about`, `browser`, `ip`, `browser_type`) VALUES
(1, 'admin', 'fd999b27737e3649a0d1ee400dd9033f', 4, '', '', 1, '', '', 1015, 0, 1539752363, 'no_ava.jpg', '', '', '', '', '0.0.0.0', ''),
(2, 'User', 'fd999b27737e3649a0d1ee400dd9033f', 1, '', '', 1, '', '', 1015, 1539785850, 1539797298, 'no_ava.jpg', '', '', '', 'Chrome 60', '0.0.0.0', 'Mozilla/1.0');

-- --------------------------------------------------------

INSERT INTO `user_settings` (`id`, `kto`, `language`, `theme`, `num`) VALUES
(1, 1, 'ru', 'default', 15),
(2, 2, 'ru', 'default', 15);

-- --------------------------------------------------------

INSERT INTO `zc_category` (`id`, `name`, `opis`, `time`) VALUES
(1, 'Скрипты', '', 1539792485),
(2, 'Обменник файлов', '', 1539792501),
(3, 'Дизайны', '', 1539792515);

-- --------------------------------------------------------

INSERT INTO `zc_file` (`id`, `kto`, `category`, `section`, `name`, `opis`, `file`, `screen`, `time`, `down`) VALUES
(1, 1, 1, 1, 'DCMS Social v. 39.8.1', 'Имеется спорная точка зрения, гласящая примерно следующее: действия представителей оппозиции, инициированные исключительно синтетически, превращены в посмешище, хотя само их существование приносит несомненную пользу обществу. Банальные, но неопровержимые выводы, а также стремящиеся вытеснить традиционное производство, нанотехнологии указаны как претенденты на роль ключевых факторов.', '000_NOMICMS_000.rar', '', 1539795676, 55),
(2, 1, 1, 2, 'VK CMS v 6.21', 'Повседневная практика показывает, что дальнейшее развитие различных форм деятельности требуют определения и уточнения дальнейших направлений развития. Значимость этих проблем настолько очевидна, что новая модель организационной деятельности способствует подготовки и реализации дальнейших направлений развития. Задача организации, в особенности же консультация с широким активом требуют определения и уточнения новых предложений. Таким образом дальнейшее развитие различных форм деятельности позволяет выполнять важные задания по разработке новых предложений.', '000_NOMICMS_000.rar', '', 1539795872, 136);

-- --------------------------------------------------------

INSERT INTO `zc_section` (`id`, `category`, `name`, `whitelist`, `max_size`, `time`) VALUES
(1, 1, 'DCMS', 'zip;rar', 25, 1539792589),
(2, 1, 'Движки', 'zip;rar', 10, 1539792603),
(3, 2, 'Аудио', 'mp3', 15, 1539792650),
(4, 2, 'Видео', 'mp4;avi', 30, 1539792679),
(5, 2, 'Фото', 'jpg;png;gif;psd', 5, 1539792722),
(6, 2, 'Разные', '', 10, 1539792746),
(7, 3, 'Шаблоны', 'zip;rar', 5, 1539792774),
(8, 3, 'Графика', 'zip;rar', 4, 1539792816);
