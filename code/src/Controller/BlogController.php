<?php
namespace App\Controller;

use App\Model\Class\Superglobals;
use Exception;
use App\Service\AuthService;
use App\Service\ArticleService;
use App\Model\ClassManager\ArticleManager;
use App\Model\ClassManager\CommentManager;

class BlogController {
    private $data;
    private ArticleManager $articleManager;
    private ArticleService $articleService;
    private CommentManager $commentManager;
    private string $url;
    private string $method;
    private $result;
    private AuthService $authService;
    private $id;
    private $superglobals;

    public function __construct(string $url, $data = null) {
        $this->superglobals = new Superglobals();
        $this->articleManager = new ArticleManager();
        $this->articleService = new ArticleService();
        $this->authService = new AuthService();
        $this->commentManager = new CommentManager();
        $this->url = $url;
        $this->data = $data;
        $this->method = $this->superglobals->get_SERVER('REQUEST_METHOD');
        $this->id = $this->superglobals->get_GET('id');
        $this->checkRoute();
    }

    private function checkRoute(): void {
        if (in_array($this->method, ['OPTIONS', 'GET'])) {
            if (strpos($this->url, 'validate-comment') !== false) {
                $this->validateComment();
            } else if ($this->id && !empty($this->id) && is_numeric($this->id)) {
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
            } else if (strpos($this->url, '/update-article') !== false) {
                $this->updateArticle();
            }
        } else if (in_array($this->method, ['OPTIONS', 'DELETE'])) {
            if (strpos($this->url, 'delete-article')) {
                $this->delete();
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
        try {
            $this->result = $this->articleService->findAll();
        } catch (Exception $e) {
            $this->result = [
                'type' => 'error',
                'status' => 400,
                'message' => $e->getMessage()
            ];
        }
    }

    public function findOneBy() {
        try {
            $article = $this->articleManager->findOneBy($this->id);

            if ($article !== null) {
                $this->result = $article->jsonSerialize();
            } else {
                throw new Exception('Article not found', 404);
            }
        } catch (Exception $e) {
            $this->result = [
                'type' => 'error',
                'status' => $e->getCode() ? $e->getCode() : 400,
                'message' => $e->getMessage()
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

    public function validateComment() {
        $articleId = filter_input(INPUT_GET, 'article_id');
        $commentId = filter_input(INPUT_GET, 'comment_id');

        if (
            $articleId &&
            !empty($articleId) &&
            is_numeric($articleId) &&
            $commentId &&
            !empty($commentId) &&
            is_numeric($commentId)
        ) {
            if ($this->authService->isAdmin()) {
                $this->commentManager->validateComment($commentId);
            } else {
                $this->result = array(
                    'status' => 401,
                    'type' => 'error',
                    'message' => "Vous n'êtes pas autorisé à valider cet article"
                );
            }
        }
    }

    public function delete() {
        try {
            $this->result = $this->articleService->delete($this->id);
        } catch (Exception $e) {
            $this->result = [
                'type' => 'error',
                'status' => $e->getCode() ? $e->getCode() : 400,
                'message' => $e->getMessage()
            ];
        }
    }

    public function updateArticle() {
        try {
            $this->result = $this->articleService->update($this->id, $this->data);
        } catch (Exception $e) {
            $this->result = [
                'type' => 'error',
                'status' => $e->getCode() ? $e->getCode() : 400,
                'message' => $e->getMessage()
            ];
        }
    }
}