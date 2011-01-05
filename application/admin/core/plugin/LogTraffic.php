<?php

/**
 * Digitalus CMS
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://digitalus-media.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@digitalus-media.com so we can send you a copy immediately.
 *
 * @category   Digitalus CMS
 * @package   Digitalus_Core_Library
 * @copyright  Copyright (c) 2007 - 2008,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id: LogTraffic.php Tue Dec 25 20:26:40 EST 2007 20:26:40 forrest lyman $
 */

class Digitalus_Controller_Plugin_LogTraffic extends Zend_Controller_Plugin_Abstract
{
    /**
     * log the current request in the traffic log
     *
     */
//    public function preDispatch(Zend_Controller_Request_Abstract $request)
//    {
////        $log = new Model_TrafficLog();
////        $log->logHit();
//    }
//    
//	public function postDispatch(Zend_Controller_Request_Abstract $request)
//    {
////        $this->getResponse()
////             ->appendBody("<p>postDispatch() called</p>\n");
//        
//        
////        echo "<pre>";
////        print_r($this->getResponse()->getBody(true));
////        echo "</pre>";
////        exit();
//    }
    
	/** 
	 * Some popular bots
	 * <Bot agent pattern> => <Bot name>
	 */
	private static $_BOTS = array(
		'/googlebot/i' => 'google',
		'/msnbot/i' => 'bing',
		'/slurp/i' => 'yahoo',
		'/baidu/i' => 'baidu',
		'/twiceler/i' => 'cuil',
		'/teoma/i' => 'ask',
		'/facebook/i' => 'facebook',
		'/technoratibot/i' => 'technorati',
	);
	
	/**
	 * Most popular web browsers
	 * 
	 * @var array
	 */
	private static $_BROWSERS = array(
		'firefox', 'msie', 'opera', 
		'chrome', 'safari', 
		'mozilla', 'seamonkey', 'konqueror', 'netscape', 
		'gecko', 'navigator', 'mosaic', 'lynx', 'amaya', 
		'omniweb', 'avant', 'camino', 'flock', 'aol',
	);
	
	public function postDispatch(Zend_Controller_Request_Abstract $request) 
	{
		if (Zend_Registry::isRegistered(Tomato_Core_GlobalKey::LOG_REQUEST) 
			&& Zend_Registry::get(Tomato_Core_GlobalKey::LOG_REQUEST) == false
		) {
			return;		
		}
		
		$uri = $request->getRequestUri();
		
		$agent = $request->getServer('HTTP_USER_AGENT');
		$browserInfo = self::_getBrowserInfo($agent);
		$log = new Tomato_Modules_Core_Model_RequestLog(
			array(
				'ip' => $request->getClientIp(),
				'agent' => $agent,
				'browser' => $browserInfo['browser'],
				'version' => $browserInfo['version'],
				'platform' => $browserInfo['platform'],
				'bot' => self::_getBot($agent),
				'uri' => $uri,
				'full_url' => $request->getScheme().'://'.$request->getHttpHost().'/'.ltrim($uri, '/'),
				'refer_url' => $request->getServer('HTTP_REFERER'),
				'access_time' => date('Y-m-d H:i:s'),
			)
		);
		$conn = Tomato_Core_Db_Connection::getMasterConnection();
		$gateway = new Tomato_Modules_Core_Model_RequestLogGateway();
		$gateway->setDbConnection($conn);
		$gateway->create($log);
	}
	
	private static function _getBrowserInfo($agent) 
	{
		$agent = strtolower($agent);
		$info = array('browser' => null, 'version' => null, 'platform' => null);
		foreach(self::$_BROWSERS as $browser) { 
            if (preg_match('#('.$browser.')[/ ]?([0-9.]*)#', $agent, $match)) { 
                $info['browser'] = $match[1] ; 
                $info['version'] = $match[2] ; 
                break;
            }
        }
		if (preg_match('/linux/', $agent)) { 
            $info['platform'] = 'linux'; 
        } elseif (preg_match('/macintosh|mac os x/', $agent)) { 
            $info['platform'] = 'mac'; 
        } elseif (preg_match('/windows|win32/', $agent)) { 
            $info['platform'] = 'windows';
        }
		return $info; 
	}
	
	private static function _getBot($agent) 
	{
		foreach (self::$_BOTS as $pattern => $name) {
			if (preg_match($pattern, $agent) == 1) {
				return $name;
			}
		}
		return null;
	}    
    
}