<?php

declare(strict_types=1);

class Application
{
    private $controller;
    private $parameters = array();
    private $controller_name;
    private $action_name;

    public function __construct()
    {
        $this->splitUrl();
        $this->createControllerAndActionNames();

        if (file_exists(Config::get('PATH_CONTROLLER') . $this->controller_name . '.php')) {

            require Config::get('PATH_CONTROLLER') . $this->controller_name . '.php';
            $this->controller = new $this->controller_name();

            if (method_exists($this->controller, $this->action_name)) {
                if (!empty($this->parameters)) {
                    call_user_func_array(array($this->controller, $this->action_name), $this->parameters);
                }
                else {
                    $this->controller->{$this->action_name}();
                }
            }
            else {
                // ex.
            }
        }
        else {
            // ex.
        }
    }

    private function splitUrl()
    {
        if (Request::get('url')) {
            $url = trim(Request::get('url'), '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);

            // set the controller and action names
            $this->controller_name = $url[0] ?? null;
            $this->action_name = $url[1] ?? null;

            // remove them from $url array
            unset($url[0], $url[1]);

            // store the url parameters
            $this->parameters = array_values($url);
        }
    }

    private function createControllerAndActionNames()
    {
        if (!$this->controller_name) {
            $this->controller_name = Config::get('DEFAULT_CONTROLLER');
        }

        if (!$this->action_name || (strlen($this->action_name) == 0)) {
            $this->action_name = Config::get('DEFAULT_ACTION');
        }

        $this->controller_name = ucwords($this->controller_name) . 'Controller';
    }
}