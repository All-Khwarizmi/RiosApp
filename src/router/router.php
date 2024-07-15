<?php

$routes = [
    '/' => base_path('/src/controllers/landing.php'),
    '/players' => base_path('/src/controllers/players.php'),
    '/player' => base_path('/src/controllers/player.php'),
    '/players/create' => base_path('/src/controllers/create-player.php'),
];

function routeToController($routes)
{
    $uri = $_SERVER['REQUEST_URI'];
    // Normalize the URI to ensure routing works as expected
    $uri = parse_url($uri, PHP_URL_PATH);

    if (array_key_exists($uri, $routes)) {
        require $routes[$uri];
    } else {
        http_response_code(404);
        echo "404 Not Found";
    }
}

// Call the routing function
routeToController($routes);
