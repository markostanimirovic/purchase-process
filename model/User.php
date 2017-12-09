<?php

namespace model;


use common\base\BaseModel;
use modelRepository\UserRepository;

class User extends BaseModel
{
    const ADMINISTRATOR = 1;
    const EMPLOYEE = 2;
    const SUPPLIER = 3;

    private $possibleRole = [self::ADMINISTRATOR, self::EMPLOYEE, self::SUPPLIER];

    protected $username;
    protected $email;
    protected $password;
    protected $repeatedPassword;
    protected $role;

    public function __construct()
    {
        parent::__construct();
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    public function getRepeatedPassword()
    {
        return $this->repeatedPassword;
    }

    public function setRepeatedPassword(string $repeatedPassword)
    {
        $this->repeatedPassword = $repeatedPassword;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole(int $role)
    {
        if (!in_array($role, $this->possibleRole)) {
            throw new \Exception('Error in setRole function: Denied value for $role variable.');
        }
        $this->role = $role;
    }

    public function populate(array $dbRow): BaseModel
    {
        $this->setUsername($dbRow['username']);
        $this->setEmail($dbRow['email']);
        $this->setPassword($dbRow['password']);
        $this->setRole($dbRow['role']);
        return parent::populate($dbRow);
    }

    public function getFieldMapping(): array
    {
        return array_merge_recursive(
            array(
                'username' => array(
                    'columnName' => '`username`',
                    'columnType' => \PDO::PARAM_STR,
                    'columnSize' => 30,
                    'columnValue' => $this->getUsername()
                ),
                'email' => array(
                    'columnName' => '`email`',
                    'columnType' => \PDO::PARAM_STR,
                    'columnSize' => 50,
                    'columnValue' => $this->getEmail()
                ),
                'password' => array(
                    'columnName' => '`password`',
                    'columnType' => \PDO::PARAM_STR,
                    'columnSize' => 30,
                    'columnValue' => $this->getPassword()
                ),
                'role' => array(
                    'columnName' => '`role`',
                    'columnType' => \PDO::PARAM_INT,
                    'columnValue' => $this->getRole()
                )
            ),
            parent::getFieldMapping()
        );
    }

    public static function getTableName(): string
    {
        return '`user`';
    }

    protected function validate(): array
    {
        $errors = array();
        $this->username = trim($this->username);
        $this->email = trim($this->email);

        if (strlen($this->username) === 0) {
            $errors['username'][] = 'Korisničko ime ne sme da bude prazno polje.';
        } else if (strlen($this->username) > 30) {
            $errors['username'][] = 'Maksimalan broj karaktera za korisničko ime je 30.';
        } else if ($this->isDuplicateUsername()) {
            $errors['username'][] = 'Korisnik sa unetim korisničkim imenom već postoji.';
        }

        if (strlen($this->email) === 0) {
            $errors['email'][] = 'E-mail ne sme da bude prazno polje.';
        } else if (strlen($this->email) > 50) {
            $errors['email'][] = 'Maksimalan broj karaktera za e-mail je 50.';
        } else if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'][] = 'E-mail mora biti u formatu example@example.com.';
        } else if ($this->isDuplicateEmail()) {
            $errors['email'][] = 'Korisnik sa unetim e-mail-om već postoji.';
        }

        if (strlen($this->password) === 0) {
            $errors['password'][] = 'Lozinka ne sme da bude prazno polje.';
        } else if (strlen($this->password) > 30) {
            $errors['password'][] = 'Maksimalan broj karaktera za lozinku je 30.';
        }

        if ($this->password !== $this->repeatedPassword) {
            $errors['repeatedPassword'][] = 'Lozinka i ponovljena lozinka se ne poklapaju.';
        }

        return $errors;
    }

    private function isDuplicateUsername(): bool
    {
        $userRepository = new UserRepository();
        $usernameColumnName = '`username`';
        $duplicateUser = $userRepository->loadOne(true, $usernameColumnName . ' = "' . $this->username . '"');

        if (empty($duplicateUser)) {
            return false;
        }

        if ($this->getId() === $duplicateUser->getId()) {
            return false;
        }
        return true;
    }

    private function isDuplicateEmail(): bool
    {
        $userRepository = new UserRepository();
        $emailColumnName = '`email`';
        $duplicateUser = $userRepository->loadOne(true, $emailColumnName . ' = "' . $this->email . '"');

        if (empty($duplicateUser)) {
            return false;
        }

        if ($this->getId() === $duplicateUser->getId()) {
            return false;
        }
        return true;
    }

    public static function getRandomPassword()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }
}