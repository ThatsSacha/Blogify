<?php

namespace App\Service;

use App\Model\Class\User;
use App\Model\ClassManager\UserManager;
use Exception;

class UserService {
    private $userManager;
    
    public function __construct()
    {
        $this->userManager = new UserManager();
    }

    public function isConnected() {
        if (isset($_SESSION['logged']) && $_SESSION['logged']) {
            return array(
                'logged' => true,
                'code' => 200
            );
        }

        return array(
            'logged' => false,
            'code' => 401
        );
    }

    /**
     * @param array $data
     * 
     * @return array
     */
    public function create(array $data): array {
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
            return $keyErrors;
        } else {
            if (filter_var($data['mail'], FILTER_VALIDATE_EMAIL)) {
                $isPasswordValid = $this->isPasswordValid($data['password']);

                if ($isPasswordValid['isValid']) {
                    $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
                    $isEmailAndPseudoUnique = $this->isEmailAndPseudoUnique($data['mail'], $data['pseudo']);

                    if (strlen($isEmailAndPseudoUnique) <= 0) {
                        $user = new User($data);
                        $insert = $this->userManager->create($user);
                        $this->logUser(new User($insert));

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
                        'message' => $isEmailAndPseudoUnique
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
                    'message' => 'Le format de l\'adresse mail est erroné'
                );
            }
        }
    }

    public function logout() {
        $_SESSION = [];
        session_destroy();
    }

    public function login(array $data) {
        if (isset($data['mail']) && !empty($data['mail']) && isset($data['password']) && !empty($data['password'])) {
            sleep(1);
            $password = $data['password'];
            $user = $this->userManager->findByMail($data['mail']);
            
            if (count($user) > 0 && password_verify($password, $user[0]['password'])) {
                $this->logUser(new User($user[0]));

                return array(
                    'type' => 'success',
                    'status' => 200,
                    'message' => 'Successfully logged in'
                );
            }
            
            return array(
                'type' => 'error',
                'status' => 401,
                'message' => 'Email et/ou mot de passe erroné'
            );
        }

        return array(
            'type' => 'error',
            'status' => 400,
            'message' => 'Email and password are mandatory and have to be filled'
        );
    }

    public function logUser(User $user): void {
        $_SESSION['logged'] = true;
        $_SESSION['user'] = array(
            'id' => $user->getId(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'mail' => $user->getMail(),
            'roles' => $user->getRoles(),
        );
    }

    /**
     * @param string $mail
     * @param string pseudo
     * 
     * @return string
     */
    public function isEmailAndPseudoUnique(string $mail, string $pseudo, $clause = null): string {
        $query = $this->userManager->findByMailOrPseudo($mail, $pseudo, $clause);
        $response = '';

        if (count($query) > 0) {
            foreach($query as $user) {
                if ($user['mail'] === $mail && $user['pseudo'] === $pseudo) {
                    $sentence = 'The mail and the pseudo are';
                    break;
                }

                if ($user['mail'] === $mail) {
                    $sentence = 'The mail is';
                }
                
                if ($user['pseudo'] === $pseudo) {
                    $sentence = 'The pseudo is';
                }
            }

            $response = "$sentence already used";
        }

        return $response;
    }

    /**
     * This function determines if password is strong enough
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

    /**
     * @return User|array
     */
    public function findOneByMail(): User|array {
        if (isset($_SESSION) && $_SESSION['logged']) {
            if (isset($_SESSION['user']['mail']) && !empty($_SESSION['user']['mail'])) {
                $users = $this->userManager->findByMail($_SESSION['user']['mail']);

                if (count($users) > 0) {
                    $user = new User($users[0]);
                    return $user;
                }
            }

            return array(
                'type' => 'error',
                'status' => 401,
                'message' => 'An error occured with this session'
            );
        }

        return array(
            'type' => 'error',
            'status' => 401,
            'message' => 'Unauthorized'
        );
    }

    public function update(array $data) {
        if (isset($_SESSION) && $_SESSION['logged']) {
            if (isset($_SESSION['user']['mail']) && !empty($_SESSION['user']['mail'])) {
                if (isset($data['firstName']) && !empty($data['firstName']) && isset($data['lastName']) && !empty($data['lastName']) && isset($data['pseudo']) && !empty($data['pseudo']) && isset($data['mail']) && !empty($data['mail'])) {
                    $firstName = htmlspecialchars($data['firstName']);
                    $lastName = htmlspecialchars($data['lastName']);
                    $pseudo = htmlspecialchars($data['pseudo']);
                    $mail = htmlspecialchars($data['mail']);

                    if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                        $users = $this->userManager->findWhereMailOrPseudoDifferent($_SESSION['user']['id'], $mail, $pseudo);

                        if (count($users) > 0) {
                            $users = $users[0];
                            $users['registeredAt'] = date_create($users['registered_at']);
                            $user = new User($users);

                            if ($user->getId() != $_SESSION['user']['id']) {
                                if ($user->getMail() === $mail || $user->getPseudo() === $pseudo) {
                                    return array(
                                        'type' => 'error',
                                        'status' => 400,
                                        'message' => "Cette adresse mail ou ce pseudo est déjà utilisé"
                                    );
                                }
                            } else {
                                $data = array(
                                    'id' => $user->getId(),
                                    'firstName' => $firstName,
                                    'lastName' => $lastName,
                                    'pseudo' => $pseudo,
                                    'mail' => $mail,
                                    'registeredAt' => $user->getRegisteredAt(),
                                    'token' => null,
                                    'tokenGeneratedAt' => null
                                );

                                $user = new User($data);
                                $insert = $this->userManager->update($user);
                                $this->logUser($user);
        
                                if (!$insert instanceof Exception) {
                                    return array('status' => 201, 'type' => 'success');
                                }
        
                                return array(
                                    'status' => 400,
                                    'type' => 'error',
                                    'message' => $insert->getMessage()
                                );
                            }
                        }
                    }

                    return array(
                        'type' => 'error',
                        'status' => 400,
                        'message' => 'Le format de l\'adresse mail est erroné'
                    );
                }

                return array(
                    'type' => 'error',
                    'status' => 400,
                    'message' => 'Tous les champs sont requis'
                );
            }

            return array(
                'type' => 'error',
                'status' => 401,
                'message' => 'An error occured with this session'
            );
        }

        return array(
            'type' => 'error',
            'status' => 401,
            'message' => 'Unauthorized'
        );
    }

    /**
     * @param array $data
     */
    public function requestPassword(array $data): array {
        if (isset($data['mail']) && !empty($data['mail'])) {
            $mail = $data['mail'];

            if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                $user = $this->userManager->findByMail($mail);

                if (count($user) > 0) {
                    $user = new User($user[0]);
                    $token = $this->createToken();
                    
                    $user->setToken($token);
                    $user->setTokenGeneratedAt(time());
                    $this->userManager->update($user);

                    $mailTemplateService = new MailTemplateService();
                    $mailService = new MailService();

                    $mailService->send(
                        null,
                        $user->getMail(),
                        null,
                        'Rénitialisez votre mot de passe !',
                        $mailTemplateService->getRequestPasswordTemplate($user)
                    );
                } else {
                    sleep(1);
                }

                return array(
                    'type' => 'success',
                    'status' => 200,
                    'message' => 'Si l\'adresse mail correspond à un compte Blogify, un mail de rénitialisation sera envoyé sur celle-ci.'
                );
            } else {
                return array(
                    'type' => 'error',
                    'status' => 400,
                    'message' => 'L\'adresse mail n\'est pas au format approprié'
                );
            }
        } else {
            return array(
                'type' => 'error',
                'status' => 400,
                'message' => 'L\'adresse mail est requise'
            );
        }
    }

    /**
     * @param array $data
     */
    public function setPassword(array $data) {
        try {
            $user = $this->userManager->findByToken($data['token']);
        
            if (count($user) > 0) {
                $password = $data['password'];
                $passwordConfirm = $data['passwordConfirm'];
    
                if ($password === $passwordConfirm) {
                    $isPasswordValid = $this->isPasswordValid($password);
                    
                    if ($isPasswordValid['isValid']) {
                        $user = new User($user[0]);
                        $password = password_hash($password, PASSWORD_BCRYPT);
                        $user->setPassword($password);
                        $user->setToken(null);
                        $user->setTokenGeneratedAt(null);
                        $this->userManager->updateWithPassword($user);
    
                        return array(
                            'type' => 'success',
                            'status' => 200,
                            'message' => 'Password has been successfully updated'
                        );
                    }

                    return array(
                        'type' => 'error',
                        'status' => 400,
                        'message' => $isPasswordValid['message']
                    );
                }
    
                return array(
                    'type' => 'error',
                    'status' => 400,
                    'message' => 'Both password didn\'t match'
                );
            }
        } catch (Exception $e) {
            return array(
                'type' => 'error',
                'status' => $e->getCode() ? $e->getCode() : 400,
                'message' => $e->getMessage()
            );
        }
    }

    /**
     * @return string $token
     */
    public function createToken(): string {
       return md5(random_bytes(25) . time());
    }

    public function resetPassword(string $token) {
        $user = $this->userManager->findByToken($token);
        
        if (count($user) > 0) {
            $user = new User($user[0]);
            $userTokenGeneratedAt = $user->getTokenGeneratedAt();
            $tokenExpirationDate = $userTokenGeneratedAt + 600;

            if ($tokenExpirationDate >= time()) {
                return array(
                    'type' => 'success',
                    'isError' => false,
                    'status' => 200,
                    'message' => 'Great'
                );
            }
        }

        return array(
            'isError' => true,
            'status' => 400,
            'message' => 'Token invalid'
        );
    }
}