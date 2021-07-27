<?php
namespace App\Controller;

use App\Model\Class\User;
use App\Model\ClassManager\UserManager;
use App\Service\UserService;

class UserController {
    private $data;
    private string $url;
    private string $method;
    private $result;

    public function __construct(string $url, $data = null) {
        $this->data = $data;
        $this->url = $url;
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->checkRoute();
    }

    private function checkRoute(): void {
        if (in_array($this->method, ['OPTIONS', 'POST'])) {
            if ($this->url === '/login-check') {
                $this->login();
            } else if ($this->url === '/logout') {
                $this->logout();
            } else if ($this->url === '/is-connected') {
                $this->isConnected();
            } else {
                $this->create();
            }
        } else if (in_array($this->method, ['OPTIONS', 'GET'])) {
            if ($this->url === '/logout') {
                $this->logout();
            }
        } else {
            $this->result = [
                'type' => 'error',
                'status' => 405,
                'message' => 'This method is not allowed'
            ];
        }
    }

    public function loadResult() {
        return $this->result;
    }

    public function login() {
        $userService = new UserService();
        $this->result = $userService->login($this->data);
    }

    public function logout() {
        $userService = new UserService();
        $userService->logout();
    }

    public function isConnected() {
        $userService = new UserService();
        $this->result = $userService->isConnected();
    }

    /**
     * This function verify all fields and if match, create user
     */
    public function create() {
        $userService = new UserService();
        $this->result = $userService->create($this->data);
    }

    public function findAll() {
        $this->result = $this->blogManager->findAll();
    }

    public function findOneBy() {
        $this->result = $this->blogManager->findOneBy($_GET['id']);
    }
}