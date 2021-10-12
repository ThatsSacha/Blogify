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

            $query = $this->db->query('SELECT * FROM user WHERE id = LAST_INSERT_ID()');
            return $query->fetchAll()[0];
        } catch (Exception $e) {
            return new Exception($e->getMessage());
        }
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
            $user = new User($response[0]);
            return $user;
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
                    pseudo = :pseudo,
                    token = :token,
                    token_generated_at = :token_generated_at
                WHERE id = :id"
            );
            
            $query->execute(array(
                ':id' => $user->getId(),
                ':first_name' => $user->getFirstName(),
                ':last_name' => $user->getLastName(),
                ':mail' => $user->getMail(),
                ':pseudo' => $user->getPseudo(),
                ':token' => $user->getToken(),
                ':token_generated_at' => $user->getTokenGeneratedAt()
            ));
        } catch (Exception $e) {
            return new Exception($e->getMessage());
        }
    }
}