<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе
 * установки. Необязательно использовать веб-интерфейс, можно
 * скопировать файл в "wp-config.php" и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define('DB_NAME', 'tz');

/** Имя пользователя MySQL */
define('DB_USER', 'root');

/** Пароль к базе данных MySQL */
define('DB_PASSWORD', 'fahrenheit');

/** Имя сервера MySQL */
define('DB_HOST', 'localhost');

/** Кодировка базы данных для создания таблиц. */
define('DB_CHARSET', 'utf8');

/** Схема сопоставления. Не меняйте, если не уверены. */
define('DB_COLLATE', '');

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'HYc=.L{QOQWy;Jhy;q*P9lZ?KhOYdy@Vn}n1d]GuqvynG6_K<CD1PM#6!MKC9fbG');
define('SECURE_AUTH_KEY',  ' A.I03H)qmCuP~3U2,3X ^vv-^& yF=ol[@uez^`8TCMW[(.Wg3A%+y!_K_{BCIx');
define('LOGGED_IN_KEY',    'Qazi]nlcF*5b/-dO,@2mQqZimL,10Be(vEdOC6kY^kmV{-J@7<`6DZxcE#;-dS[%');
define('NONCE_KEY',        'j1F8~jfAnFltUYS=!0R.;<amX8yq-h_bgG`t!Q1),_1_>b|U:,:ps}dNm0p?%qFj');
define('AUTH_SALT',        '(T#fJ,4BxR0KQ}_Kw,rv0dYN7fk7jU2<!*ua1!b#qsj,$l{*sm;k[ij=ggsvYFRC');
define('SECURE_AUTH_SALT', '<L%#pu?lhP[82=^+1Z)&Ww5.hGAzytgY08BKuG}[z j/gJ0_cXeesSe#@[MKA[7.');
define('LOGGED_IN_SALT',   's<NNc7 eq=&1h $zV {Su:gNmms)BY{&>66?V:.xA.[dbtyx=2~QOP9WwN~>AB95');
define('NONCE_SALT',       '9  Gb-RO;[7@Hk@](!sSdeehJWMUvdXR+vgwDCV`[[aGgqJl1Cy@`gP/hdhdnY*P');

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix  = 'emisv_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в Кодексе.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Инициализирует переменные WordPress и подключает файлы. */
require_once(ABSPATH . 'wp-settings.php');
