<?php

function connectDb()
{
    try {
        $conn = new PDO("mysql:host=127.0.0.1;dbname=authentication", 'root', 'root');
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        return null;
    }
}

function logUser($email, $password) {
    try {
        $connexion = connectDb();
        $sql = 'SELECT * FROM users WHERE email = :email';
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        } else {
            return null;
        }
    } catch (PDOException $e) {
        return null;
    }
}

function getUser($id) {
    $connexion = connectDb();
    $sql = 'SELECT * FROM users WHERE id = ' . $id;
    $stmt = $connexion->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function saveUser($username, $email, $hashedPassword) {
    try {
        $connexion = connectDb();
        $sql = 'INSERT INTO users (username, email, password) VALUES (:username, :email, :password)';
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        return $stmt->execute();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}