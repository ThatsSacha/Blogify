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
     * @return User|null
     */
    public function findOneBy($id): User|null {
        $query = $this->db->query(
            'SELECT * FROM user WHERE id = ' . $id
        );
        $query->execute();

        $response = $query->fetchAll();

        if (count($response) > 0) {
            $response = $response[0];
            //
        }
        
        return null;
    }

    public function findByMailOrPseudo(string $mail = null, string $pseudo = null) {
        $query = $this->db->prepare(
            'SELECT * FROM user WHERE mail = :mail OR pseudo = :pseudo'
        );

        $query->execute(array(
            'mail' => $mail,
            'pseudo' => $pseudo
        ));

        return $query->fetchAll();
    }

    public function findWhereMailOrPseudoDifferent(int $id, string $mail = null, string $pseudo = null) {
        $query = $this->db->prepare(
            'SELECT * FROM user WHERE mail = :mail OR pseudo = :pseudo'
        );

        $query->execute(array(
            'mail' => $mail,
            'pseudo' => $pseudo
        ));

        return $query->fetchAll();
    }

    public function findByMail(string $mail) {
        $query = $this->db->prepare(
            'SELECT * FROM user WHERE mail = :mail'
        );
        $query->execute(array(
            'mail' => $mail
        ));

        return $query->fetchAll();
    }

    public function update(User $user) {
        try {
            $query = $this->db->prepare(
                "UPDATE user SET
                    first_name = :first_name,
                    last_name = :last_name,
                    mail = :mail,
                    pseudo = :pseudo
                WHERE mail = :mail"
            );
            
            $query->execute(array(
                $user->getFirstName(),
                $user->getLastName(),
                $user->getMail(),
                $user->getPseudo()
            ));
        } catch (Exception $e) {
            return new Exception($e->getMessage());
        }
    }
}