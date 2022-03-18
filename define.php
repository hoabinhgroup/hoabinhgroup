<?php

defined('_LOUIS') || define('_LOUIS', true);
defined('DS') || define('DS', DIRECTORY_SEPARATOR);


//Duong dan den thu muc chua ung dung
defined('APPLICATION_PATH')
	|| define('APPLICATION_PATH',
			  realpath(dirname(__FILE__) . '/application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV',
              (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV')
                                         : 'developer'));   // hoặc production (chế độ cho người dùng)

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

//thu muc template
define('TEMPLATE', 'news2017');

define('PATH_NEWS', 'public/images/news/');

define('PATH_TIMELINE', 'public/images/timeline/');

define('PATH_TESTIMONIALS', '/public/images/testimonial/');

//thu muc public
define('SHARE', 'public');

define('CACHE_DIR', APPLICATION_PATH.'/cache/');

//Duong dan den thu muc /public
define('PUBLIC_PATH', realpath(dirname(__FILE__) . '/'. SHARE));

//Duong dan tuyet doi den thu muc /templates
define('TEMPLATE_PATH', PUBLIC_PATH. '/templates/'. TEMPLATE);

//Duong dan den thu muc /templates
define('TEMPLATE_URL', '/'.SHARE.'/templates/'. TEMPLATE);

