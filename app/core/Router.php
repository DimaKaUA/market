<?php 

/*
Class Router to determine the requested page.
> Clings classes of controllers and models;
> Instantiates pages controllers and causes of action of these controllers.
*/
class Router {

    private $routes;

    public function __construct()
    {
        $routesPath = ROOT . '/config/routes.php';
        $this->routes = include($routesPath);
    }

    /**
     * Returns request string
     * @return string
     */
    private function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }

    public function start()
    {
        // Get string of request
        $uri = $this->getURI();

        // Check such request into routes.php
        foreach ($this->routes as $uriPattern => $path) {
            // Compare $uriPattern and $uri
            if (preg_match("~{$uriPattern}~", $uri)) {
                // Get internal path from external path 
                // in accordance with rull
                $internalRoute = preg_replace("~$uriPattern~", $path, $uri);
                // Determin controller, action, params
                $segments = explode('/', $internalRoute);

                $controllerName = ucfirst(array_shift($segments));
                $actionName = array_shift($segments);
                $params = $segments;

                // adding prefixes
                $controllerName = $controllerName . 'Controller';
                $actionName = $actionName . 'Action';

                // pick up the file from the controller class
                $controllerFile = $controllerName . '.php';
                $controllerPath = ROOT . "/controllers/" . $controllerFile;
                if(file_exists($controllerPath)){
                    include_once $controllerPath;
                }

                // If the correct controller class hasn't been 
                // load or there is no correct method - 404 
                if(!is_callable(array($controllerName, $actionName))){
                    //header("HTTP/1.0 404 Not Found");
                    $this->errorPage404();
                    return;
                }
                
                // create the controller
                $controller = new $controllerName;
                $action = $actionName;

                // call the action of the controller
                $result = call_user_func_array(array($controller, $action), $params);
                
                if ($result != null) {
                    break;
                }
            }
        }
    }
    
    public static function errorPage404()
    {
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.1 404 Not Found');
        header("Status: 404 Not Found");
        header('Location:'.$host.'not-found');
    }
}
