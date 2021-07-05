<?php

namespace App\Class;

use App\Model\Class\AbstractClass;

class Database extends AbstractClass {
    private string $host;
    private string $databaseName;
    private string $user;
    private string $password;

    public function __construct(array $data = []) {
        parent::__construct($data);
    }

    public function getHost(): string {
        return $this->host;
    }

    public function setHost(string $host): void {
        $this->host = $host;
    }

    public function getDatabaseName(): string {
        return $this->databaseName;
    }

    public function setDatabaseName(string $databaseName): void {
        $this->databaseName = $databaseName;
    }

    public function getUser(): string {
        return $this->user;
    }

    public function setUser(string $user): void {
        $this->user = $user;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }
}