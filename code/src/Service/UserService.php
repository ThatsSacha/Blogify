<?php

namespace App\Service;

use App\Model\Class\User;
use App\Model\ClassManager\UserManager;
use Exception;
use PDOException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer;

class UserService {
    private $userManager;

    public function __construct() 
    {
        $this->userManager = new UserManager();
    }
    /**
     * @param array $data
     */
    public function create(array $data) {
        $keyErrors = array();
        $mandatoryFields = array('firstName', 'lastName', 'mail', 'password', 'pseudo');

        foreach($mandatoryFields as $key) {
            if (!isset($data[$key]) || empty($data[$key])) {
                array_push($keyErrors, array(
                    'type' => 'error',
                    'message' => "$key key is missing or empty")
                );
            }
        }

        if (count($keyErrors) > 0) {
            $keyErrors['message'] = 'Fields error';
            $keyErrors['status'] = 400;
            $keyErrors['type'] = 'error';

            $keyErrors = array_reverse($keyErrors);

            $this->result = $keyErrors;
        } else {
            if (filter_var($data['mail'], FILTER_VALIDATE_EMAIL)) {
                $isPasswordValid = $this->isPasswordValid($data['password']);

                if ($isPasswordValid['isValid']) {
                    $data['password'] = $this->hashPassword($data['password']);
                    $isEmailAndPseudoUnique = $this->isEmailAndPseudoUnique($data['mail'], $data['pseudo']);

                    if (count($isEmailAndPseudoUnique) <= 0) {
                        $user = new User($data);
                        $insert = $this->userManager->create($user);

                        if (!$insert instanceof Exception) {
                            return array('status' => 201, 'type' => 'success');
                        }

                        return array(
                            'status' => 400,
                            'type' => 'error',
                            'message' => $insert->getMessage()
                        );
                    }
                    
                    return array(
                        'type' => 'error',
                        'status' => 400,
                        'messages' => $isEmailAndPseudoUnique
                    );
                } else {
                    return array(
                        'type' => 'error',
                        'status' => 400,
                        'message' => $isPasswordValid['message']
                    );
                }
            } else {
                return array(
                    'type' => 'error',
                    'status' => 400,
                    'message' => 'The email format is wrong'
                );
            }
        }
    }

    /**
     * @param string $password
     * 
     * @return string The hashed password
     */
    public function hashPassword(string $password): string {
        $firstSalt = '*P56K4oBih0&li3dsPeY%g3^2';
        $secondSalt = '51L$N2ln8qsY4i6QB2C@$gv9a';

        $password = $firstSalt . $password . $secondSalt;
        $password = hash('sha512', $password);
        $password = strtoupper($password);

        return $password;
    }

    public function isEmailAndPseudoUnique(string $mail, string $pseudo): array {
        $query = $this->userManager->findByMailAndPseudo($mail, $pseudo);
        $response = array();

        if (count($query) > 0) {
            foreach($query as $user) {
                if ($user['mail'] === $mail) {
                    array_push($response, array(
                        'message' => 'This mail is already used')
                    );
                }
                
                if ($user['pseudo'] === $pseudo) {
                    array_push($response, array(
                        'message' => 'This pseudo is already used')
                    );
                }
            }
        }

        return $response;
    }

    /**
     * This function determines if password is strong
     * 
     * @param string $password
     * @return array
     */
    private function isPasswordValid(string $password): array {
        if (strlen($password) >= 6) {
            $passwordNumercis = array();

            foreach(str_split($password) as $character) {
                if (is_numeric($character)) {
                    array_push($passwordNumercis, $character);
                }
            }

            if (count($passwordNumercis) < 3) {
                return array(
                    'isValid' => false,
                    'message' => 'Your password should have 3 number at least'
                );
            }

            return array(
                'isValid' => true
            );
        }
        
        return array(
            'isValid' => false,
            'message' => 'Your password should be equal or longer than 6 characters'
        );
    }
}