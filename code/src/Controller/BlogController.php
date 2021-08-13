<?php
namespace App\Controller;
use Exception;
use App\Service\AuthService;
use App\Service\ArticleService;
use App\Model\ClassManager\ArticleManager;

class BlogController {
    private $data;
    private ArticleManager $articleManager;
    private ArticleService $articleService;
    private string $url;
    private string $method;
    private $result;
    private $authService;

    public function __construct(string $url, $data = null) {
        $this->articleManager = new ArticleManager();
        $this->articleService = new ArticleService();
        $this->url = $url;
        $this->data = $data;
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->checkRoute();
    }

    private function checkRoute(): void {
        $this->authService = new AuthService();

        if (in_array($this->method, ['OPTIONS', 'GET'])) {
            if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
                $this->findOneBy();
            } else {
                $this->findAll();
            }
        } else if (in_array($this->method, ['OPTIONS', 'POST'])) {
            if ($this->url === '/add-article') {
                if ($this->authService->isLogged()) {
                    if ($this->authService->isAdmin() || $this->authService->isSuperAdmin()) {
                        $this->create();
                    } else {
                        $this->result = [
                            'type' => 'error',
                            'status' => 401,
                            'message' => 'You don\'t have the rights to add an article'
                        ];
                    }
                } else {
                    $this->result = [
                        'type' => 'error',
                        'status' => 401,
                        'message' => 'You have to be logged in to add an article'
                    ];
                }
            } else if ($this->url === '/add-comment') {
                $this->addComment();
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

    public function findAll() {
        $this->result = $this->articleManager->findAll();
    }

    public function findOneBy() {
        $article = $this->articleManager->findOneBy($_GET['id']);

        if ($article !== null) {
            $this->result = $article->jsonSerialize();
        } else {
            $this->result = $this->result = [
                'type' => 'error',
                'status' => 404,
                'message' => 'Not found'
            ];
        }
    }

    public function create() {
        try {
            $this->result = $this->articleService->create($this->data);
        } catch (Exception $e) {
            $this->result = [
                'type' => 'error',
                'status' => 400,
                'message' => $e->getMessage()
            ];
        }
    }

    public function addComment() {
        try {
            $this->result = $this->articleService->addComment($this->data);
        } catch (Exception $e) {
            $this->result = [
                'type' => 'error',
                'status' => $e->getCode() ? $e->getCode() : 400,
                'message' => $e->getMessage()
            ];
        }
    }
}