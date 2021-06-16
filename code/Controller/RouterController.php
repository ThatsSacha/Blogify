<?php

$url = $_SERVER['REQUEST_URI'];

if ($url === '/') {
    returnView('index');
}

else if (strpos($url, 'blog')) {
    returnView('blog');
}

else {
    returnView('Not found', 404);
}

function returnView(string $viewName, int $statusCode = 200) {
    require '../View/' . $viewName . '.php';
    http_response_code($statusCode);
}