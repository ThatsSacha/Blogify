<?php

namespace App\Model\ClassManager;

use App\Model\Class\Comment;
use PDO;
use Exception;
use PDOException;

class CommentManager {
    private PDO|PDOException $db;

    public function __construct()
    {
        $db = new DatabaseManager();
        $this->db = $db->connect();
    }

    public function create(Comment $data) {
        try {
            $query = $this->db->prepare(
                "INSERT INTO comment
                    (
                        comment,
                        user_id,
                        article_id
                    )
                    VALUES(
                        ?, ?, ?
                    );"
            );
            
            $query->execute(array(
                $data->getComment(),
                $data->getUserId(),
                $data->getArticleId()
            ));
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getByArticleId(int $id): array {
        $query = $this->db->query(
            'SELECT * FROM comment WHERE article_id = ' . $id
        );
        $query->execute();
        $response = $query->fetchAll();
        $comments = [];
        
        if (count($response) > 0) {
            foreach($response as $comment) {
                $comment['created_at'] = date_create($comment['created_at']);
                $comments[] = new Comment($comment);
            }
        }
        
        return $comments;
    }
}