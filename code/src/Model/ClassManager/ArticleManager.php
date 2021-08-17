<?php

namespace App\Model\ClassManager;

use PDO;
use Exception;
use PDOException;
use App\Model\Class\Article;

class ArticleManager {
    private PDO|PDOException $db;

    public function __construct()
    {
        $db = new DatabaseManager();
        $this->db = $db->connect();
    }

    /**
     * @return array
     */
    public function findAll(): array {
        $query = $this->db->query(
            'SELECT * FROM article'
        );
        $query->execute();

        $responses = $query->fetchAll();
        $articles = [];

        if (count($responses) > 0) {
            foreach($responses as $response) {
                $response['created_at'] = date_create($response['created_at']);
                $response['author_id'] = (int) $response['author_id'];
                $articles[] = new Article($response);
            }
        }

        return $articles;
    }

    /**
     * @param $id
     * 
     * @return Article|null
     */
    public function findOneBy($id): Article|null {
        $query = $this->db->query(
            'SELECT * FROM article WHERE id = ' . $id
        );
        $query->execute();

        $response = $query->fetchAll();

        if (count($response) > 0) {
            $response = $response[0];

            $article = new Article(array(
                'id' => $response['id'],
                'title' => $response['title'],
                'teaser' => $response['teaser'],
                'content' => $response['content'],
                'cover' => $response['cover'],
                'coverCredit' => $response['cover_credit'],
                'authorId' => $response['author_id'] === null ? 0 : $response['author_id'],
                'createdAt' => date_create($response['created_at']),
                'updatedAt' => $response['updated_at'] === null ? null : date_create($response['updated_at'])
            ));
    
            return $article;
        }
        
        return null;
    }

    public function create(Article $data) {
        try {
            $query = $this->db->prepare(
                "INSERT INTO article
                    (
                        title,
                        teaser,
                        content,
                        cover,
                        cover_credit,
                        author_id
                    )
                    VALUES(
                        ?, ?, ?, ?, ?, ?
                    );"
            );
            
            $query->execute(array(
                $data->getTitle(),
                $data->getTeaser(),
                $data->getContent(),
                $data->getCover(),
                $data->getCoverCredit(),
                $data->getAuthorId()
            ));
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}