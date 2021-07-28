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
    private UserService $userService;

    public function __construct(string $url, $data = null) {
        $this->userService = new UserService();
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
            } else if (strpos($this->url, '/update')) {
                $this->update();
            } else {
                $this->create();
            }
        } else if (in_array($this->method, ['OPTIONS', 'GET'])) {
            if ($this->url === '/logout') {
                $this->logout();
            } else if ($this->url === '/profile') {
                $this->findOneBy();
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
        $this->result = $this->userService->login($this->data);
    }

    public function logout() {
        $userService = new UserService();
        $userService->logout();
    }

    public function isConnected() {
        $this->result = $this->userService->isConnected();
    }

    /**
     * This function verify all fields and if match, create user
     */
    public function create() {
        $this->result = $this->userService->create($this->data);
    }

    public function findAll() {
        $this->result = $this->blogManager->findAll();
    }

    public function findOneBy() {
        $resp = $this->userService->findOneByMail();

        if ($resp instanceof User) {
            $resp = $resp->jsonSerialize();
        }

        $this->result = $resp;
    }

    public function update(): void {
        $this->result = $this->userService->update($this->data);
    }
}