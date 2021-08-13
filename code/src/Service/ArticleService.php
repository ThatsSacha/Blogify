<?php

namespace App\Service;

use App\Model\Class\Comment;
use App\Model\Class\Article;
use App\Model\ClassManager\ArticleManager;
use App\Model\ClassManager\CommentManager;
use Exception;

class ArticleService {
    private ArticleManager $articleManager;
    private ImportFileService $importFileService;
    private AuthService $authService;
    private CommentManager $commentManager;

    public function __construct()
    {
        $this->articleManager = new ArticleManager();
        $this->importFileService = new ImportFileService();
        $this->authService = new AuthService();
        $this->commentManager = new CommentManager();
    }

    public function create(array $data) {
        if (isset($_FILES['cover'])) {
            $data['cover'] = $_FILES['cover']['name'];
        }
       
        $mandatoryFields = ['title', 'teaser', 'content', 'coverCredit', 'cover'];
        $this->verifyMandatoryFields($data, $mandatoryFields);
        $data['cover'] =  $this->importFileService->verifyAndUploadFile($_FILES['cover']);

        $data['authorId'] = $_SESSION['user']['id'];
        $article = new Article($data);
        $this->articleManager->create($article);
    }

    public function verifyMandatoryFields(array $data, array $mandatoryFields) {
        $errors = [];
        foreach($mandatoryFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                $errors[] = "$field est manquant ou vide";   
            }
        }
        
        if (count($errors) > 0) {
            $error = implode(', ', $errors);
            throw new Exception($error);
        }
    }

    public function addComment(array $data) {
        if ($this->authService->isLogged()) {
            $mandatoryFields = ['articleId', 'comment'];
            $this->verifyMandatoryFields($data, $mandatoryFields);
    
            $data['user_id'] = $_SESSION['user']['id'];
            $comment = new Comment($data);
            $this->commentManager->create($comment);
        } else {
            throw new Exception('Vous devez être connecté', 401);
        }
    }
}