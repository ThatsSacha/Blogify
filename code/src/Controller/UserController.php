<?php
namespace App\Controller;

use App\Model\Class\Superglobals;
use App\Model\Class\User;
use App\Service\UserService;

class UserController {
    private $data;
    private string $url;
    private string $method;
    private $result;
    private UserService $userService;
    private $token;
    private $superglobals;

    public function __construct(string $url, $data = null) {
        $this->superglobals = new Superglobals();
        $this->userService = new UserService();
        $this->data = $data;
        $this->url = $url;
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->token = isset($_GET['token']) ? $_GET['token'] : null; 
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
            } else if ($this->url ==='/request-password') {
                $this->requestPassword();
            } else if ($this->url ==='/set-password') {
                $this->setPassword();
            } else if (strpos($this->url, '/users/validate') !== false) {
                $this->validateUser();
            } else {
                $this->create();
            }
        } else if (in_array($this->method, ['OPTIONS', 'GET'])) {
            if ($this->url === '/logout') {
                $this->logout();
            } else if ($this->url === '/profile') {
                if (count($_SESSION) > 0 && $_SESSION['logged']) {
                    $this->findOneBy();
                } else {
                    header('Location: /');
                }
            } else if (strpos($this->url, '/reset-password') !== false) {
                $this->resetPassword();
            } else if (strpos($this->url, '/users/not-validated') !== false) {
                $this->findUsersNotValidated();
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

    public function requestPassword(): void {
        $this->result = $this->userService->requestPassword($this->data);
    }

    public function resetPassword(): void {
        if ($this->token) {
            $this->result = $this->userService->resetPassword($this->token);
        } else {
            $this->result = array(
                'isError' => true,
                'status' => 400,
                'message' => 'Token not found'
            );
        }
    }

    public function setPassword(): void {
        if (isset($this->data['token'])) {
            $this->result = $this->userService->setPassword($this->data);
        } else {
            $this->result = array(
                'isError' => true,
                'status' => 400,
                'message' => 'Token not found'
            );
        }
    }

    public function findUsersNotValidated(): void {
        $this->result = $this->userService->getUsersNotValidated();
    }

    public function validateUser(): void {
        $this->result = $this->userService->validateUser($this->data);
    }
}