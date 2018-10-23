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
define('DB_NAME', 'freelancingDB');

/** Имя пользователя MySQL */
define('DB_USER', 'admin');

/** Пароль к базе данных MySQL */
define('DB_PASSWORD', '');

/** Имя сервера MySQL */
define('DB_HOST', 'localhost');

/** Кодировка базы данных для создания таблиц. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         ' 5i-KEB,`wVJ[1{:(o7I$7^{|vVVF&P|H+G^RWL4_fN)%g5c)#kG)Vwi51(tc{z*');
define('SECURE_AUTH_KEY',  '&h8e0N,F&B9Jg.BK) Hd>*[&4drQCnnmSagRjM-Vo$x#O;hOP5ah:*?DkfywSd~L');
define('LOGGED_IN_KEY',    '(;g)O0F61WJ4!913Yi*!I&v7J6 ),;3WuA{p4i/`t,VL7R3+yannoX+%uD.aGIc/');
define('NONCE_KEY',        '<sE)MD<O@ci}Y(S(5^}^ h$Gcy NzRRZf{Le)z=9N-sQSJp6Q,7hnDU XS-~n/jM');
define('AUTH_SALT',        'E.$5jReWjOWGEPZoivb4>w&7$~V$M-.A/dpe2YQP{:d>C$_&d}|.LZ$:H3ofE@]+');
define('SECURE_AUTH_SALT', 'CU2N0xFn;](q.-V8_7(O$]M:EA~P?@T%=Vjy8*SYc(%OxcUM!iG417`fs2Z|sv^K');
define('LOGGED_IN_SALT',   'jJCi*wLXz*R]e;gssR)RlAPIdc/GRUtR 27<~?QpPT+HOW.e{&l[[jT3LmD*:^_@');
define('NONCE_SALT',       '{TfxIH|,jeSQ8yTSMm*^gr,KpKmHIZh;L6*Q5<-jJ-GvAu> refAFp@>`+fhMs{g');

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix  = 'wp_';

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
define('WP_ALLOW_REPAIR', ture);