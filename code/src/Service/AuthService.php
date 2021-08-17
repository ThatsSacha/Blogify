<?php

namespace App\Service;

class AuthService {
    public function isLogged(): bool {
        return isset($_SESSION) && isset($_SESSION['logged']) && $_SESSION['logged'] ? true : false;
    }

    public function isAdmin(): bool {
        if (isset($_SESSION) && isset($_SESSION['user']) && $this->isLogged()) {
            if (in_array('ROLE_ADMIN', $_SESSION['user']['roles']) || in_array('ROLE_SUPERADMIN', $_SESSION['user']['roles'])) {
                return true;
            }

            return false;
        }

        return false;
    }

    public function isSuperAdmin(): bool {
        if (isset($_SESSION) && isset($_SESSION['user']) && $this->isLogged()) {
            if (in_array('ROLE_SUPERADMIN', $_SESSION['user']['roles'])) {
                return true;
            }

            return false;
        }

        return false;
    }
}