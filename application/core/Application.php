<?php

declare(strict_types=1);

class Application
{
    private $controller;
    private $parameters = array();
    private $controllerName;
    private $actionName;

    public function __construct()
    {
        $this->splitUrl();
        $this->createControllerAndActionNames();
        if (file_exists(Config::get('PATH_CONTROLLER') . $this->controllerName . '.php')) {

            // load this file and create this controller
            // example: if controller would be "car", then this line would translate into: $this->car = new car();
            require Config::get('PATH_CONTROLLER') . $this->controllerName . '.php';
            $this->controller = new $this->controllerName();

            // check for method: does such a method exist in the controller ?
            if (method_exists($this->controller, $this->actionName)) {

                if (!empty($this->parameters)) {
                    // call the method and pass arguments to it
                    call_user_func_array(array($this->controller, $this->actionName), $this->parameters);
                } else {
                    // if no parameters are given, just call the method without parameters, like $this->index->index();
                    $this->controller->{$this->actionName}();
                }
            } else {
                require Config::get('PATH_CONTROLLER') . 'ErrorController.php';
                $this->controller = new ErrorController;
                $this->controller->error404();
            }
        }
        else {
            require Config::get('PATH_CONTROLLER') . 'ErrorController.php';
            $this->controller = new ErrorController;
            $this->controller->error404();
        }
    }

    private function splitUrl()
    {
        if (Request::get('url')) {
            $url = trim(Request::get('url'), '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);

            // set the controller and action names
            $this->controllerName = $url[0] ?? null;
            $this->actionName = $url[1] ?? null;

            // remove them from $url array
            unset($url[0], $url[1]);

            // store the url parameters
            $this->parameters = array_values($url);
        }
    }

    private function createControllerAndActionNames()
    {
        if (!$this->controllerName) {
            $this->controllerName = Config::get('DEFAULT_CONTROLLER');
        }

        if (!$this->actionName || (strlen($this->actionName) == 0)) {
            $this->actionName = Config::get('DEFAULT_ACTION');
        }

        $this->controllerName = ucwords($this->controllerName) . 'Controller';
    }
}