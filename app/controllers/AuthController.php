<?php 

/**
* Authorization
*/
class AuthController {
    
    public static function checkAuth()
    {

        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'У вас нет доступа к этому разделу сайта';
            exit;
        } else {
            
            if ($_SERVER['PHP_AUTH_USER'] === 'admin'
            	&& $_SERVER['PHP_AUTH_PW'] === '12345') {
            	$_SESSION['AUTH'] = 1;
            	return true;
            } 
            
            Router::errorPage404();
        }
    }
}