<?php 
class Product_Bootstrap extends Zend_Application_Module_Bootstrap{
	
 	protected function _initRoutes()
	{
		$front_controller = Zend_Controller_Front::getInstance();
        $router = $front_controller->getRouter();
        
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
         
          $route = new Zend_Controller_Router_Route_Regex(
        'gallery/([a-zA-Z-_0-9]+)\.html',
        array(
            'controller' => 'gallery',
            'action'     => 'view',
             'module'        => 'default',
        ),
        array(
            1 => 'ident'
        ),
        'gallery/%s.html'
		);
    	$router->addRoute('gallery-detail', $route);
    	
    	 $route = new Zend_Controller_Router_Route(
            'video',
            array(
                'action'        => 'index',
                'controller'    => 'video',
                'module'        => 'default',
                
            )
        );

        $router->addRoute('video', $route);
        
        
             $route = new Zend_Controller_Router_Route(
            'video/:ident',
            array(
                'action'        => 'category',
                'controller'    => 'video',
                'module'        => 'default',
                
            ),
            array(
                'ident' => '[a-zA-Z-_0-9]+'
            )
        );
         $router->addRoute('video-ident', $route);
         
           $route = new Zend_Controller_Router_Route_Regex(
        'video/([a-zA-Z-_0-9]+)\.html',
        array(
            'controller' => 'video',
            'action'     => 'view',
             'module'        => 'default',
        ),
        array(
            1 => 'ident'
        ),
        'video/%s.html'
		);
    	$router->addRoute('video-detail', $route);
         

	}
} 