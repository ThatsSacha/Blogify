<?php
namespace App\Controller;
use App\Model\ClassManager\ArticleManager;

class BlogController {
    private ArticleManager $blogManager;
    private string $url;
    private string $method;
    private $result;

    public function __construct(string $url) {
        $this->blogManager = new ArticleManager();
        $this->url = $url;
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->checkRoute();
    }

    private function checkRoute(): void {
        if (in_array($this->method, ['OPTIONS', 'GET'])) {
            if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
                $this->findOneBy();
            } else {
                $this->findAll();
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
        $this->result = $this->blogManager->findAll();
    }

    public function findOneBy() {
        $article = $this->blogManager->findOneBy($_GET['id']);

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
}