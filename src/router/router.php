<?php

ob_start(); // Start output buffering.

class Router
{
    protected $routes = [];

    public function get($uri, $controller)
    {

        return $this->add($uri, $controller, 'GET');
    }
    public function post($uri, $controller)
    {
        return $this->add($uri, $controller, 'POST');
    }

    public function put($uri, $controller)
    {
        return $this->add($uri, $controller, 'PUT');
    }

    public function delete($uri, $controller)
    {
        return $this->add($uri, $controller, 'DELETE');
    }



    public function route($uri, $method)
    {
        foreach ($this->routes as $route) {
            if ($route['uri'] === $uri && $route['method'] === $method) {


                if ($route["middleware"] === "auth") {
                    $isLogged = $_SESSION["user"]["isLogged"] ?? false;
                    if (!$isLogged) {
                        header("Location: /login");
                        exit;
                    }
                } else if ($route["middleware"] === "gest") {
                    $isLogged = $_SESSION["user"]["isLogged"] ?? false;
                    if ($isLogged) {
                        header("Location: /");
                        exit;
                    }
                }
                require $route['controller'];
            }
        }
        // if no route is found
        require base_path('/views/404.view.php');
    }

    public function add($uri, $controller, $method)
    {
        $this->routes[] = [
            'uri' => $uri,
            'controller' => $controller,
            'method' => $method,
            "middleware" => null
        ];

        return $this;
    }

    public function only($key)
    {
        $this->routes[count($this->routes) - 1]["middleware"] = $key;
    }
}

$router = new Router();
require base_path('/src/router/routes.php');
// uri and method come from the index.php file where the router is required
$router->route(
    $uri,
    $method
);

ob_end_flush(); // End buffering and flush all output to client.
