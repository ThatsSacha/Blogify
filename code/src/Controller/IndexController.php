<?php
namespace App\Controller;

use App\Service\ArticleService;
use Superglobals;

class IndexController {
    private string $url;
    private string $method;
    private $result;
    private $articleService;
    private $superglobals;

    public function __construct(string $url, $data = null) {
        $this->superglobals = new Superglobals();
        $this->articleService = new ArticleService();
        $this->url = $url;
        $this->data = $data;
        $this->method = $this->superglobals->get_SERVER('REQUEST_METHOD');
        $this->checkRoute();
    }

    private function checkRoute(): void {
        if (in_array($this->method, ['OPTIONS', 'GET'])) {
            $this->findLastArticles();
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

    public function findLastArticles(): void {
        $this->result = $this->articleService->findLastArticles();
    }
}