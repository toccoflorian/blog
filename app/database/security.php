<?php

require_once __DIR__ . '/app/database.php';

class AuthDB
{
    function __construct(private PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    function login(string $email, string $password): void
    {
        $statementUser = $this->pdo->prepare('SELECT * FROM user WHERE email=:email');
        $statementUser->bindValue(':email', $email);
        $statementUser->execute();
        $user = $statementUser->fetch();
        if (!$user) {
            $error['email'] = ERROR_NO_USER;
        } elseif (!password_verify($password, $user['password'])) {
            $error['password'] = ERROR_INVALID_PASSWORD;
        } else {
            $statementSession = $this->pdo->prepare('INSERT INTO session VALUES(
                :id,
                :userid
                
            )');
            $sessionId = bin2hex(random_bytes(64));
            $sessionSignature = hash_hmac('sha256', $sessionId, 'lekkjdjzeozdjezojdlezkzefredozedozdzdkezffz');
            $statementSession->bindValue(':id', $sessionId);
            $statementSession->bindValue(':userid', $user['id']);
            $statementSession->execute();
            setcookie('session', $sessionId, time() + 60 * 60 * 24 * 14, '', '', false, true);
            setcookie('signature', $sessionSignature, time() + 60 * 60 * 24 * 14, '', '', false, true);
            header('location:profile.php');
        }
    }
    function logout(): void
    {
        $sessionId = $_COOKIE['session'] ?? '';
        $statement = $this->pdo->prepare('DELETE FROM session Where id=:userid');
        $statement->bindValue(':userid', $sessionId);
        $statement->execute();
        setcookie('session', '', time() - 1);
        setcookie('signature', '', time() - 1);
        header('location:/');
    }
    function register(array $user): void
    {
        $statement = $this->pdo->prepare('INSERT INTO user VALUES (
            DEFAULT,
            :firstname,
            :lastname,
            :email,
            :password
        )');
        $statement->bindValue('firstname', $user['firstname']);
        $statement->bindValue('lastname', $user['lastname']);
        $statement->bindValue('email', $user['email']);
        $statement->bindValue('password', password_hash($user['password'], PASSWORD_ARGON2I));
        $statement->execute();
    }
    function islogged(): array | false
    {
        $sessionId = $_COOKIE['session'] ?? '';
        $statement = $this->pdo->prepare('SELECT * FROM session WHERE id=:sessionid');
        $statement->bindValue(':sessionid', $sessionId);
        $statement->execute();
        $session = $statement->fetch();
        if ($session) {
            $signature = $_COOKIE['signature'] ?? '';
            $verifyingSignature = hash_hmac('sha256', $sessionId, 'lekkjdjzeozdjezojdlezkzefredozedozdzdkezffz');
            if ($signature === $verifyingSignature) {
                $statementUser = $this->pdo->prepare('SELECT * FROM user WHERE id=:userid');
                $statementUser->bindValue(':userid', $session['iduser']);
                $statementUser->execute();
                return $statementUser->fetch();
            }
        }
        return false;
    }
}


return new AuthDB($pdo);
