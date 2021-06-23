<?php
namespace App\Controller;

class RouterController {
    private $url;

    public function __construct()
    {
        $this->url = $_SERVER['REQUEST_URI'];
        $this->checkRoute();
    }

    public function checkRoute() {
        $url = $this->url;

        if ($url === '/') {
            $this->returnView('index');
        }
        
        else if ($url === '/blog') {
            $this->returnView('blog', 200, BlogController::class);
        }
        
        else {
            $this->returnView('error', 404);
        }
    }
    
    private function returnView(string $viewName, int $statusCode = 200, $class = null) {
        $controllerName = __DIR__ . '/' . ucfirst($viewName) . 'Controller.php';
        $controllerExists = is_file($controllerName);
    
        if ($controllerExists) {
            $test = new $class();
        }
    
        require __DIR__ . '../../View/'. $viewName .'.php';
        http_response_code($statusCode);
    }
}