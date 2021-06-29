<?php

namespace App\Model\ClassManager;

use App\Model\Class\Article;
use PDO;
use PDOException;

class BlogManager {
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
                $article = new Article(
                    $response['id'],
                    $response['title'],
                    $response['teaser'],
                    $response['content'],
                    $response['cover'],
                    $response['author_id'],
                    date_create($response['created_at']),
                    $response['updated_at'] === null ? null : date_create($response['updated_at'])
                );
    
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

            $article = new Article(
                $response['id'],
                $response['title'],
                $response['teaser'],
                $response['content'],
                $response['cover'],
                $response['author_id'],
                date_create($response['created_at']),
                $response['updated_at'] === null ? null : date_create($response['updated_at'])
            );
    
            return $article->jsonSerialize();
        }
        
        return array();
    }
}