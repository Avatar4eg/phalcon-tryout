<?php
/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
use Phalcon\Config;

defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

return new Config([
    'version' => '1.0.0',

    'database' => [
        'adapter'  => 'Mysql',
        'host'     => 'localhost',
        'username' => 'root',
        'password' => '',
        'dbname'   => 'phalcon-tryout',
        'charset'  => 'utf8',
    ],

    'video_service' => [
        'video_app'         => 'ffmpeg',
        'video_source'      => 'rtmp://localhost:1935/tv/mp4:superchannel',
        'video_length'      => 60 * 60,
        'new_video_offset'  => 5 * 60,
        'wsdl_url'          => 'https://localhost/WebServices/Schedule.asmx?WSDL',
        'output_path'       => BASE_PATH . '/public_html/storage/video/',
    ],

    'application' => [
        'appDir'         => APP_PATH . '/',
        'modelsDir'      => APP_PATH . '/common/models/',
        'migrationsDir'  => APP_PATH . '/migrations/',
        'cacheDir'       => BASE_PATH . '/cache/',

        // This allows the baseUri to be understand project paths that are not in the root directory
        // of the webpspace.  This will break if the public_html/index.php entry point is moved or
        // possibly if the web server rewrite rules are changed. This can also be set to a static path.
        'baseUri'        => preg_replace('/public_html([\/\\\\])index.php$/', '', $_SERVER['PHP_SELF']),
    ],

    /**
     * if true, then we print a new line at the end of each CLI execution
     *
     * If we dont print a new line,
     * then the next command prompt will be placed directly on the left of the output
     * and it is less readable.
     *
     * You can disable this behaviour if the output of your application needs to don't have a new line at end
     */
    'printNewLine' => true
]);
