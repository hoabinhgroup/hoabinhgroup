<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap{

	protected function _initSession(){
		Zend_Session::start();
	}


	protected function _initLocale()
	{
		$ns = new Zend_Session_Namespace('language');
		$lang = $ns->lang;
		if (empty($ns->lang)){
			 $lang = 'vi';
			 }
		$locale = new Zend_Locale($lang);
		Zend_Registry::set('Zend_Locale', $locale);

	}

	protected function _initTranslate() {
    // Get Locale
    $locale = Zend_Registry::get('Zend_Locale');

    $translate = new Zend_Translate(
                    array(
                        'adapter' => 'array',
                        'content' => APPLICATION_PATH . '/languages/' . $locale . '.php',
                        'locale' => $locale)
    );



    Zend_Form::setDefaultTranslator($translate);

    // Save it for later
    Zend_Registry::set('Zend_Translate', $translate);
}

	 protected function _initZFDebug() {
        $identity = Zend_Auth::getInstance()->getIdentity();

        if (isset($identity) && ($identity->id == 3)) {
/*
            $dbResource = $this->getPluginResource('db');
            $dbAdapter = $dbResource->getDbAdapter();

            $cacheResource = $this->getPluginResource('cachemanager');
            $cacheManager = $cacheResource->getCacheManager();
            $cache = $cacheManager->getCache('default');
            $cacheBackend = $cache->getBackend();

            $autoloader = Zend_Loader_Autoloader::getInstance();
            $autoloader->registerNamespace('ZFDebug');

            $options = array(
                'plugins' => array(
                    'Variables',
                    'Database' => array('adapter' => $dbAdapter),
                    'File' => array('basePath' => APPLICATION_PATH),
                    'Cache' => array('backend' => $cacheBackend),
                    'Exception'
                )
            );
            $debug = new ZFDebug_Controller_Plugin_Debug($options);
*/

/*
            $this->bootstrap('frontController');
            $frontController = $this->getResource('frontController');
            $frontController->registerPlugin($debug);
*/
        }
    }

	protected function _initSearch(){
	Zend_Search_Lucene_Search_QueryParser::setDefaultEncoding('utf-8');
Zend_Search_Lucene_Analysis_Analyzer::setDefault(
    new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8_CaseInsensitive ()
);
		}

	 protected function _initCachemanager(){
        $cacheManager=new Zend_Cache_Manager;
        $dbcache=array(
            'frontend'=>array(
                'name'=>'Core',
                'options'=>array(
                    'lifetime'=>null,
                    'automatic_serialization'=>'true',
                ),
            ),
            'backend'=>array(
                'name'=>'File',
                'options'=>array(
                    'cache_dir'=>CACHE_DIR,
                ),
            ),
        );
        $cacheManager->setCacheTemplate('dbcache',$dbcache);
        return $cacheManager;
    }


    protected function _initPreloading()
	{
		$this->bootstrap('frontController');
		$front = Zend_Controller_Front::getInstance();
	   $cd = $front->getControllerDirectory();
	   $modules = array_keys($cd);

	   foreach($modules as $module):
     $FUNC_DIR = APPLICATION_PATH . DS . "modules" . DS . $module . DS . 'helpers' . DS;
	 $scan = glob("$FUNC_DIR*");
        foreach ($scan as $path) {
            if (preg_match('/\_helper.php$/', $path)) {
                require_once $path;
            }

        }

    	endforeach;
	}

	 protected function _initRoutes()
    {
    	$front_controller = Zend_Controller_Front::getInstance();
        $router = $front_controller->getRouter();

      //  echo getIpClient();

           $this->bootstrap('frontController');
            $frontController = $this->getResource('frontController');
       $front = Zend_Controller_Front::getInstance();
	   $cd = $front->getControllerDirectory();
	   $moduleNames = array_keys($cd);
         //echo __METHOD__;

         $params = explode('/',$_SERVER["REQUEST_URI"]);
          if ($params[1] == 'admin'){
        // if (($params[1] == 'admin') && (getIpClient() == '118.70.186.15')){
		 if (isset($params[2])){


	       	if(in_array($params[2], $moduleNames)){

          $route = new Zend_Controller_Router_Route(
            'admin/'.$params[2].'/:action/*',
            array(
	            'action' => 'index',
                'controller'    => 'admin-'.$params[2],
                'module'        => $params[2],
            )
        );

        $router->addRoute('admin', $route);


        }else{

         $route = new Zend_Controller_Router_Route(
            'admin/'.$params[2].'/:action/*',
            array(
	            'action' => 'index',
                'controller'    => 'admin-'.$params[2],
                'module'        => 'default',
            )
        );

        $router->addRoute('admindefault', $route);


}
        }elseif(!isset($params[2])){
	         $route = new Zend_Controller_Router_Route(
            'admin',
            array(
	            'action' => 'index',
                'controller'    => 'admin-index',
                'module'        => 'default',
            )
        );

        $router->addRoute('adminindex', $route);
        }

        }elseif(($params[1] == 'tin-tuc') && (!isset($params[2]) || (int) $params[2]) ){

	         $route = new Zend_Controller_Router_Route(
            'tin-tuc/:page',
            array(
                'action'        => 'index',
                'controller'    => 'index',
                'module'        => 'new',
                'page'        => 1,
                'lang'        => 'vi',

            )
        );

        $router->addRoute('danh-sach-tin-tuc', $route);



        }elseif(($params[1] == 'news') && (!isset($params[2]) || (int) $params[2])){

	          $route = new Zend_Controller_Router_Route(
            'news/:page/',
            array(
                'action'        => 'index',
                'controller'    => 'index',
                'module'        => 'new',
                'page'        =>  1,
                'lang'        => 'en',

            )
        );

        $router->addRoute('news', $route);
        }

        elseif(isset($params[2] )){

	         if($params[2] == 'tin-noi-bo'){
	       $route = new Zend_Controller_Router_Route(
            'tin-tuc/tin-noi-bo',
            array(
                'action'        => 'internal',
                'controller'    => 'index',
                'module'        => 'new',
                'lang'        => 'vi'

            )
        );

        $router->addRoute('tin-noi-bo', $route);

        	}else{
	        	 $route = new Zend_Controller_Router_Route(
            'tin-tuc/:ident/:page',
            array(
                'action'        => 'index',
                'controller'    => 'index',
                'module'        => 'new',
                'page'        => 1,
                'lang'        => 'vi',

            ),
            array(
                'ident' => '[a-zA-Z-_0-9]+'
            )
        );

        $router->addRoute('tin-tuc-category', $route);


         $route = new Zend_Controller_Router_Route(
            'linh-vuc/:ident',
            array(
                'action'        => 'field',
                'controller'    => 'single',
                'module'        => 'new',
                'folder'        => 'linh-vuc',
                'lang'        => 'vi',

            ),
            array(
                'ident' => '[a-zA-Z-_0-9]+'
            )
        );

        $router->addRoute('linh-vuc-category', $route);


         $route = new Zend_Controller_Router_Route(
            'field/:ident',
            array(
                'action'        => 'field',
                'controller'    => 'single',
                'module'        => 'new',
                'folder'        => 'field',
                'lang'        => 'en',

            ),
            array(
                'ident' => '[a-zA-Z-_0-9]+'
            )
        );

        $router->addRoute('field-category', $route);

        	}


	        if($params[2] == 'internal'){
	         $route = new Zend_Controller_Router_Route(
            'news/internal',
            array(
                'action'        => 'internal',
                'controller'    => 'index',
                'module'        => 'new',
                'lang'        => 'en'

            )
        );

        $router->addRoute('news-internal', $route);

        }else{


         $route = new Zend_Controller_Router_Route(
            'news/:ident/:page/',
            array(
                'action'        => 'index',
                'controller'    => 'index',
                'module'        => 'new',
                'page'        => '1',
                'lang'        => 'en',

            ),
            array(
                'ident' => '[a-zA-Z-_0-9]+'
            )
        );

        $router->addRoute('news-category', $route);





	         $route = new Zend_Controller_Router_Route(
            'pages/:ident',
            array(
                'action'        => 'index',
                'controller'    => 'pages',
                'module'        => 'default',


            ),
            array(
                'ident' => '[a-zA-Z-_0-9]+'
            )
        );

        $router->addRoute('pagesmmm', $route);
        }

	        }else {

	           $route = new Zend_Controller_Router_Route(
            '/:ident',
            array(
                'action'        => 'index',
                'controller'    => 'single',
                'module'        => 'new',

            )
        );

        $router->addRoute('single', $route);

        $route = new Zend_Controller_Router_Route(
            'authorize',
            array(
                'action'        => 'authorize',
                'controller'    => 'single',
                'module'        => 'new',
            )
        );

        $router->addRoute('authorize', $route);

        $route = new Zend_Controller_Router_Route(
            'lien-he',
            array(
                'action'        => 'index',
                'controller'    => 'contact',
                'module'        => 'default',
                'ident'        => 'lien-he',
                'lang'        => 'vi',
            )
        );

        $router->addRoute('lien-he', $route);

         $route = new Zend_Controller_Router_Route(
            'contact',
            array(
                'action'        => 'index',
                'controller'    => 'contact',
                'module'        => 'default',
                'ident'        => 'contact',
                'lang'        => 'en',
            )
        );

        $router->addRoute('contact', $route);

		 $route = new Zend_Controller_Router_Route_Static(
            'ban-tin',
            array(
                'action'        => 'internal',
                'controller'    => 'index',
                'module'        => 'new',
                'lang'        => 'vi'

            )
        );

        $router->addRoute('ban-tin', $route);


        }


       /*  $route = new Zend_Controller_Router_Route_Static(
            'tin-tuc',
            array(
                'action'        => 'error',
                'controller'    => 'error',
                'module'        => 'error',

            )
        );

        $router->addRoute('tin-tuc-index', $route);
     */




    //   if(($params[1] == 'tuyen-dung') && (is_numeric($params[2] = true))){
        $route = new Zend_Controller_Router_Route(
            'tuyen-dung/:page',
            array(
                'action'        => 'index',
                'controller'    => 'recruitment',
                'module'        => 'default',
                'lang'        => 'vi',
                'content_type'        => 'Tuyển dụng',
                'page'        => 1,

            )
        );

        $router->addRoute('tuyen-dung', $route);

          $route = new Zend_Controller_Router_Route(
            'recruitment/:page',
            array(
                'action'        => 'index',
                'controller'    => 'recruitment',
                'module'        => 'default',
                'lang'        => 'en',
                'content_type'        => 'Recruitment',
                'page'        => 1,

            )
        );

        $router->addRoute('recruitment', $route);




           $route = new Zend_Controller_Router_Route_Regex(
        'tuyen-dung/([a-zA-Z-_0-9]+)\.html',
        array(
            'controller' => 'recruitment',
            'action'     => 'view',
             'module'        => 'default',
        ),
        array(
            1 => 'ident'
        ),
        '/%s.html'
		);
    	$router->addRoute('chi-tiet-tuyen-dung', $route);


         $route = new Zend_Controller_Router_Route(
            'gallery',
            array(
                'action'        => 'index',
                'controller'    => 'gallery',
                'module'        => 'default',

            )
        );

        $router->addRoute('gallery', $route);


         $route = new Zend_Controller_Router_Route(
            'gallery/:ident',
            array(
                'action'        => 'category',
                'controller'    => 'gallery',
                'module'        => 'default',

            ),
            array(
                'ident' => '[a-zA-Z-_0-9]+'
            )
        );
         $router->addRoute('gallery-ident', $route);





         $route = new Zend_Controller_Router_Route(
            'doi-ngu-lanh-dao',
            array(
                'action'        => 'leader',
                'controller'    => 'gallery',
                'module'        => 'default',

            )
        );

        $router->addRoute('doi-ngu-lanh-dao', $route);


          $route = new Zend_Controller_Router_Route_Static(
            'en',
            array(
                'action'        => 'index',
                'controller'    => 'index',
                'module'        => 'default',
                'lang'        => 'en',

            )
        );

        $router->addRoute('Trang-tieng-anh', $route);

          $route = new Zend_Controller_Router_Route(
            'leadership-team',
            array(
                'action'        => 'leader',
                'controller'    => 'gallery',
                'module'        => 'default',

            )
        );

        $router->addRoute('leadership-team', $route);


      /*   $route = new Zend_Controller_Router_Route(
            'testimonial',
            array(
                'action'        => 'index',
                'controller'    => 'testimonial',
                'module'        => 'default',

            )
        );

        $router->addRoute('testimonial', $route);

           $route = new Zend_Controller_Router_Route(
            'testimonial/:ident',
            array(
                'action'        => 'detail',
                'controller'    => 'testimonial',
                'module'        => 'default',

            )
        );

        $router->addRoute('testimonial-ident', $route);

         */




         $route = new Zend_Controller_Router_Route(
            'tag/:ident/:page/*',
            array(
                'action'        => 'view',
                'controller'    => 'tag',
                'module'        => 'default',
                'page'        => '1',

            ),
            array(
                'ident' => '[a-zA-Z-_0-9]+'
            )
        );

          $router->addRoute('tag', $route);




         $route = new Zend_Controller_Router_Route_Regex(
        '([a-zA-Z-_0-9]+)\.html',
        array(
	        'category'   => 'null',
            'controller' => 'index',
            'action'     => 'view',
             'module'        => 'new',
        ),
        array(
            1 => 'ident'
        ),
        '/%s.html'
		);
    	$router->addRoute('product-news', $route);



    	     $route = new Zend_Controller_Router_Route_Regex(
        'tin-tuc/([a-zA-Z-_0-9]+)\.html',
        array(
            'controller' => 'index',
            'action'     => 'view',
             'module'        => 'new',
             'lang'        => 'vi',
        ),
        array(
            1 => 'ident'
        ),
        '/%s.html'
		);
    	$router->addRoute('chi-tiet-tin', $route);


    	     $route = new Zend_Controller_Router_Route_Regex(
        'news/([a-zA-Z-_0-9]+)\.html',
        array(
            'controller' => 'index',
            'action'     => 'view',
             'module'        => 'new',
             'lang'        => 'en',
        ),
        array(
            1 => 'ident'
        ),
        '/%s.html'
		);
    	$router->addRoute('product-news', $route);


    	 $route = new Zend_Controller_Router_Route_Regex(
        'gallery/([a-zA-Z-_0-9]+)\.html',
        array(
	          'action'     => 'view',
            'controller' => 'gallery',
             'module'        => 'default',
        ),
        array(
            1 => 'ident'
        ),
        'gallery/%s.html'
		);
    	$router->addRoute('gallery-detail', $route);


	}


	public function _initAutoload(){
		Zend_Controller_Action_HelperBroker::addPath(
APPLICATION_PATH .'/helpers');

        $front = Zend_Controller_Front::getInstance();
        $front->registerPlugin(new Louis_Plugin_Permission());
       // $front->registerPlugin(new Louis_Plugin_Sanitize());
        $front->registerPlugin(new Zend_Controller_Plugin_ErrorHandler(array(
                                'module'     => 'error',
                                'controller' => 'error',
                                'action'     => 'error'
        )));


    }


  protected function _initLayout(){
    $layout = explode('/', $_SERVER['REQUEST_URI']);

    if(in_array('admin', $layout)){
        $layout_dir = 'admin';
    }else if(in_array('default', $layout)){
        $layout_dir = 'default';
    }
    else{
        $layout_dir = 'default';

    }
      $options = array(
             'layout'     => 'layout',
             'layoutPath' => TEMPLATE_PATH ."/".$layout_dir."/layouts"
      );
    Zend_Layout::startMvc($options);
	}



	public function setConstants($constants)
	{
    // define() is notoriously slow
    if (function_exists('apc_define_constants')) {
        apc_define_constants('zf', $constants);
        return;
    }

    foreach ($constants as $name => $value) {
        if (false === defined($name)) {
            define($name, $value);
        }
    }
	}

	protected function _initDatabase(){
        $db = $this->getPluginResource('db')->getDbAdapter();
        Zend_Db_Table::setDefaultAdapter($db); //important
        Zend_Registry::set('db', $db);
	}

	/*
	protected function _initFrontcontroller()
	{
		$front = Zend_Controller_Front::getInstance();
		$front->setControllerDirectory(APPLICATION_PATH . "modules/default/controllers");
		return $front;
	}
	*/

	/*  protected function _initMenus()
	 {
    $view = $this->getResource('view');
    $view->mainMenuId = 2;
    $view->adminMenuId = 3;
	}

	*/


}
