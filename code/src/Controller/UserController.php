<?php
namespace App\Controller;

use App\Model\Class\User;
use App\Model\ClassManager\UserManager;

class UserController {
    private UserManager $userManager;
    private string $url;
    private string $method;
    private $result;

    public function __construct(string $url) {
        $this->userManager = new UserManager();
        $this->url = $url;
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->checkRoute();
    }

    private function checkRoute(): void {
        if (in_array($this->method, ['OPTIONS', 'POST'])) {
            /*if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
                $this->findOneBy();
            } else {
                $this->findAll();
            }*/
            $this->create();
        } else {
            $this->result = [
                'type' => 'error',
                'status' => 405,
                'message' => 'This method is not allowed'
            ];
        }
    }

    public function loadResult() {
        return $this->result;
    }

    /**
     * This function verify all fields and if match, create user
     */
    public function create() {
        $data = json_decode(file_get_contents('php://input'), true);
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
                    $this->result = $isEmailAndPseudoUnique;

                    if ($isEmailAndPseudoUnique) {
                        $this->userManager->create(new User($data));
                    } else {
                        $this->result = array(
                            'type' => 'error',
                            'status' => 400,
                            'message' => $isEmailAndPseudoUnique['message']
                        );
                    }
                } else {
                    $this->result = array(
                        'type' => 'error',
                        'status' => 400,
                        'message' => $isPasswordValid['message']
                    );
                }
            } else {
                $this->result = array(
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

    public function isEmailAndPseudoUnique(string $mail, string $pseudo): array|bool {
        $query = $this->userManager->findByMailAndPseudo($mail, $pseudo);

        if (count($query) > 0) {
            $response = array();

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

            return $response;
        }

        return true;
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

    public function findAll() {
        $this->result = $this->blogManager->findAll();
    }

    public function findOneBy() {
        $this->result = $this->blogManager->findOneBy($_GET['id']);
    }
}