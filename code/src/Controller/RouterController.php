<?php
namespace App\Controller;

use App\Service\AuthService;
use Exception;

class RouterController {
    private $url;
    public $json = null;
    public AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
        $this->url = $_SERVER['REQUEST_URI'];
        $this->checkRoute();
    }

    public function checkRoute() {
        $url = $this->url;
        
        if ($url === '/') {
            $this->returnView('index', 200, IndexController::class);
        }
        
        else if ($url === '/blog' || strpos($url, '/delete-article') !== false || strpos($url, '/validate-comment') !== false || strpos($url, 'blog') || strpos($url, 'add-comment')) {
            $this->returnView('blog', 200, BlogController::class);
        }

        else if (strpos($url, '/update-article') !== false) {
            $this->returnView('update-article', 200, BlogController::class, true);
        }

        else if (strpos($url, '/add-article') !== false) {
            $this->returnView('add-article', 200, BlogController::class, true);
        }

        else if (strpos($url, '/article') !== false) {
            $this->returnView('article', 200, BlogController::class);
        }

        else if ($url === '/register') {
            $this->returnView('register');
        }

        else if ($url === '/login') {
            $this->returnView('login');
        }

        else if (strpos($url, 'user/update') || $url === '/user' || $url === '/logout' || $url === '/login-check') {
            $this->returnView('user', 200, UserController::class);
        }

        else if ($url === '/profile') {
            $this->returnView('profile', 200, UserController::class, true);
        }

        else if ($url === '/is-connected') {
            $this->returnView('user', 200, UserController::class);
        }

        else if ($url === '/contact') {
            $this->returnView('contact');
        }

        else if ($url === '/send-message') {
            $this->returnView('contact', 200, ContactController::class);
        }

        else if ($url === '/about') {
            $this->returnView('about');
        }
        
        else {
            $this->returnView('error', 404);
        }
    }
    
    private function returnView(string $viewName, int $statusCode = 200, $class = null, $require_login = false) {
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

        if ($_SERVER['HTTP_ACCEPT'] === 'application/json' && method_exists($class, 'loadResult')) {
            isset($class->loadResult()['status'])
                && 
            $class->loadResult()['type'] === 'error' 
                ?
            $statusCode = $class->loadResult()['status']
                : 
            $statusCode;

            echo $this->json;
        } else {
            if ($require_login && !isset($_SESSION['logged']) ) {
                require __DIR__ . '../../View/error-401.php';
            } else {
                $this->json = json_decode($this->json, true);
                
                if (isset($this->json['type']) && $this->json['type'] === 'error') {
                    require __DIR__ . '../../View/error-404.php';
                } else {
                    $view = __DIR__ . '../../View/'. $viewName .'.php';
                    require $view;
                }
            }
        }

        http_response_code($statusCode);
    }
}