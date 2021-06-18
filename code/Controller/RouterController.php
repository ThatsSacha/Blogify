<?php
$url = $_SERVER['REQUEST_URI'];

if ($url === '/') {
    returnView('index');
}

else if ($url === '/blog') {
    returnView('blog');
}

else {
    returnView('error', 404);
}

function returnView(string $viewName, int $statusCode = 200) {
    $controllerName = __DIR__ . '/' . ucfirst($viewName) . 'Controller.php';
    $controllerExists = is_file($controllerName);

    if ($controllerExists) {
        require $controllerName;
    }

    require __DIR__ . '../../View/'. $viewName .'.php';
    http_response_code($statusCode);
}