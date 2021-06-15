<?php

$url = $_SERVER['REQUEST_URI'];

if ($url === '/') {
    returnView('send home view');
}

else if (strpos($url, 'blog')) {
    returnView('send blog view');
}

else {
    returnView('Not found', 404);
}

function returnView(string $viewName, int $statusCode = 200) {
    echo $viewName;
    http_response_code($statusCode);
}