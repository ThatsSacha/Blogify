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
        
        else if ($url === '/blog' || strpos($url, 'blog')) {
            $this->returnView('blog', 200, BlogController::class);
        }

        else if ($url === '/login') {
            $this->returnView('login');
        }

        else if ($url === '/user' || $url === '/login-check' || $url === '/logout') {
            $this->returnView('user', 200, UserController::class);
        }
        
        else {
            $this->returnView('error', 404);
        }
    }
    
    private function returnView(string $viewName, int $statusCode = 200, $class = null) {
        $controllerName = __DIR__ . '/' . ucfirst($viewName) . 'Controller.php';
        $controllerExists = is_file($controllerName);
    
        if ($controllerExists) {
            if (isset($_POST) && count($_POST) > 0) {
                $input = $_POST;
            } else {
                $input = json_decode(file_get_contents('php://input'), true);
            }
            
            $data = $input;
            $class = new $class($this->url, $data);
        }

        if ($_SERVER['HTTP_ACCEPT'] === 'application/json') {
            isset($class->loadResult()['status'])
                && 
            $class->loadResult()['type'] === 'error' 
                ?
            $statusCode = $class->loadResult()['status']
                : 
            $statusCode;

            if ($class->loadResult() !== null) {
                echo json_encode($class->loadResult());
            }
        } else {
            require __DIR__ . '../../View/'. $viewName .'.php';
        }

        http_response_code($statusCode);
    }
}