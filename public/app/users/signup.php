<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';


// Has to be fullName with big N for some reason
if (
    isset($_POST['fullName'],
    $_POST['email'],
    $_POST['username'],
    $_POST['password'],
    $_POST['confirmPassword'])
) {
    $fullName = trim(filter_var($_POST['fullName'], FILTER_SANITIZE_STRING));
    $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
    $username = trim(filter_var($_POST['username'], FILTER_SANITIZE_STRING));
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    // Confirm password
    if ($password !== $confirmPassword) {
        $_SESSION['errors'] = "The passwords do not match";
        redirect('/');
    }
    // No conflicts
    if (existsInDatabase($pdo, 'users', 'email', $email)) {
        $_SESSION['errors'] = "Email already taken";
        redirect('/');
    }
    // Not taken, no spaces, 3-12 characters
    if (!isValidUsername($pdo, $username)) {
        redirect('/');
    }

    // Criterias met -> Insert
    $statement = $pdo->prepare('INSERT INTO users (fullname, username, email, password) 
    VALUES (:fullname, :username, :email, :password)');
    pdoErrorInfo($pdo, $statement);

    $statement->execute([
        ':fullname' => $fullName,
        ':username' => $username,
        ':email' => $email,
        ':password' => password_hash($password, PASSWORD_BCRYPT)
    ]);

    $id = $pdo->lastInsertId();
    $_SESSION['user']['id'] = $id;
    $_SESSION['greeting'] = "Welcome $username!";

    redirect('/');
}
// Back to mainpage
redirect('/');
