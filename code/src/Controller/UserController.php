<?php
namespace App\Controller;

use App\Model\Class\User;
use App\Model\ClassManager\UserManager;
use App\Service\UserService;

class UserController {
    private UserManager $userManager;
    private string $url;
    private string $method;
    private $result;
    private $userService;

    public function __construct(string $url) {
        $this->userManager = new UserManager();
        $this->url = $url;
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->checkRoute();
    }

    private function checkRoute(): void {
        if (in_array($this->method, ['OPTIONS', 'POST'])) {
            /*if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
                $this->findOneBy();
            } else {
                $this->findAll();
            }*/
            $this->create();
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

    /**
     * This function verify all fields and if match, create user
     */
    public function create() {
        $data = json_decode(file_get_contents('php://input'), true);
        $userService = new UserService();
        $this->result = $userService->create($data);
    }

    public function findAll() {
        $this->result = $this->blogManager->findAll();
    }

    public function findOneBy() {
        $this->result = $this->blogManager->findOneBy($_GET['id']);
    }
}