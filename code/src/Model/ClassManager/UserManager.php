<?php

namespace App\Model\ClassManager;

use App\Model\Class\User;
use Exception;
use PDO;
use PDOException;

class UserManager {
    private PDO|PDOException $db;

    public function __construct()
    {
        $db = new DatabaseManager();
        $this->db = $db->connect();
    }

    public function create(User $data) {
        try {
            $query = $this->db->prepare(
                "INSERT INTO user
                    (
                        first_name,
                        last_name,
                        mail,
                        password,
                        pseudo,
                        roles
                    )
                    VALUES(
                        ?, ?, ?, ?, ?, ?
                    );"
            );
            
            $query->execute(array(
                $data->getFirstName(),
                $data->getLastName(),
                $data->getMail(),
                $data->getPassword(),
                $data->getPseudo(),
                json_encode($data->getRoles())
            ));
        } catch (Exception $e) {
            return new Exception($e->getMessage());
        }
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

    public function findByMailAndPseudo(string $mail, string $pseudo) {
        $query = $this->db->prepare(
            'SELECT * FROM user WHERE mail = :mail OR pseudo = :pseudo'
        );
        $query->execute(array(
            'mail' => $mail,
            'pseudo' => $pseudo
        ));

        return $query->fetchAll();
    }
}