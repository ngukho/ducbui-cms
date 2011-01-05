<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL | E_STRICT);

    // Define base path obtainable throughout the whole application
    defined('BASE_PATH')
        || define('BASE_PATH', realpath(dirname(__FILE__)));
    // Define path to application directory
    defined('APPLICATION_PATH')
        || define('APPLICATION_PATH', BASE_PATH . '/application');
    // Define application environment
    defined('APPLICATION_ENV')
        || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
        
   	define("BASE_URL",str_replace("/".basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']));
	define('DIR_UPLOAD_MEDIA',BASE_PATH . '/media');
    	
    // Set include path to Zend (and other) libraries
    set_include_path(BASE_PATH . '/library' .
//        PATH_SEPARATOR . APPLICATION_PATH . '/models' .
        PATH_SEPARATOR . get_include_path() .
        PATH_SEPARATOR . '.'
    );

//	require_once 'Zend/Loader/Autoloader.php';
//	$autoloader = Zend_Loader_Autoloader::getInstance();
//	$autoloader->setDefaultAutoloader(create_function('$class',"include str_replace('_', '/', \$class) . '.php';"));
    
    // Require Zend_Application
    require_once 'Zend/Application.php';

    // Create application
    $application = new Zend_Application(
        APPLICATION_ENV,
        APPLICATION_PATH . '/configs/application.ini'
#        APPLICATION_PATH . '/data/config.xml'
    );
    // Bootstrap, and run application
    $application->bootstrap()
                ->run();
#Zend_Debug::dump($application);


// Co the su dung cach cau hinh file index cu (vsp) de khong che load tung thu vien
// Khi do , phai cau hinh lai thu muc model va dung them cach cau hinh cua Digitalus 1.5 (tao file plugin de khoi tao bien)
//http://ganeshhs.com/zend-framework/zend-framework-tutorial-part-2-bootstrap-file

?>