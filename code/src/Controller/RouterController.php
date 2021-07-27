<?php
namespace App\Controller;

class RouterController {
    private $url;
    public $result;
    public $json = null;

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

        else if ($url === '/register') {
            $this->returnView('register');
        }

        else if ($url === '/user' || $url === '/login-check' || $url === '/logout') {
            $this->returnView('user', 200, UserController::class);
        }

        else if ($url === '/profile') {
            $this->returnView('profile', 200, UserController::class);
        }

        else if ($url === '/is-connected') {
            $this->returnView('user', 200, UserController::class);
        }
        
        else {
            $this->returnView('error', 404);
        }
    }
    
    private function returnView(string $viewName, int $statusCode = 200, $class = null) {
        $controllerName = str_replace('App\\Controller\\', '', $class) . '.php';
        $controllerExists = is_file(__DIR__ . '/' . $controllerName);
        
        if ($controllerExists) {
            if (isset($_POST) && count($_POST) > 0) {
                $input = $_POST;
            } else {
                $input = json_decode(file_get_contents('php://input'), true);
            }
            
            $data = $input;
            $class = new $class($this->url, $data);
        }

        if (isset($class) && method_exists($class, 'loadResult') && $class->loadResult() !== null) {
            $this->json = json_encode($class->loadResult());
        }

        if ($_SERVER['HTTP_ACCEPT'] === 'application/json') {
            isset($class->loadResult()['status'])
                && 
            $class->loadResult()['type'] === 'error' 
                ?
            $statusCode = $class->loadResult()['status']
                : 
            $statusCode;

            echo $this->json;
        } else {
            $this->result = $this->json;
            //echo $this->result;
            require __DIR__ . '../../View/'. $viewName .'.php';
        }

        http_response_code($statusCode);
    }
}