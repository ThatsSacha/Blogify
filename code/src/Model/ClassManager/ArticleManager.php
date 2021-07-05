<?php

namespace App\Model\ClassManager;

use App\Model\Class\Article;
use PDO;
use PDOException;

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
        $articles = array();
        
        if (count($responses) > 0) {
            foreach($responses as $response) {
                $article = new Article(array(
                    'id' => $response['id'],
                    'title' => $response['title'],
                    'teaser' => $response['teaser'],
                    'content' => $response['content'],
                    'cover' => $response['cover'],
                    'authorId' => $response['author_id'],
                    'createdAt' => date_create($response['created_at']),
                    'updatedAt' => $response['updated_at'] === null ? null : date_create($response['updated_at'])
                ));
    
                array_push($articles, $article->jsonSerialize());
            }
        } else {
            array_push($articles, array());
        }

        return $articles;
    }

    /**
     * @param $id
     * 
     * @return array
     */
    public function findOneBy($id): array {
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
                'authorId' => $response['author_id'],
                'createdAt' => date_create($response['created_at']),
                'updatedAt' => $response['updated_at'] === null ? null : date_create($response['updated_at'])
            ));
    
            return $article->jsonSerialize();
        }
        
        return array();
    }
}