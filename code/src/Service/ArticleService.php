<?php

namespace App\Service;

use App\Model\Class\Article;
use App\Model\ClassManager\ArticleManager;
use Exception;

class ArticleService {
    private ArticleManager $articleManager;
    private ImportFileService $importFileService;

    public function __construct()
    {
        $this->articleManager = new ArticleManager();
        $this->importFileService = new ImportFileService();
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
            if (isset($data[$field]) && !empty($data[$field])) {
                
            } else {
                $errors[] = "$field est manquant ou vide";
            }
        }

        if (count($errors) > 0) {
            $error = implode(', ', $errors);
            throw new Exception($error);
        }
    }
}