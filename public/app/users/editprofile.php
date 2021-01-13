<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';

if (!userIsLoggedIn()) {
    redirect('/');
}

$id = $_SESSION['user']['id'];



if (isset($_FILES['image'])) {
    $image = $_FILES['image'];
    $user = getUserById($pdo, $id);

    // Avatars
    if (!isValidImage($image)) {
        redirect('/editprofile.php');
    }

    $fileName = createFileName($image['type']);

    if (!move_uploaded_file($image['tmp_name'], '../../uploads/avatars/' . $fileName)) {
        $_SESSION['errors'] = "Oopsie!";
        redirect('/editprofile.php');
    }

    $statement = $pdo->prepare('UPDATE users SET avatar = :avatar WHERE id = :id');
    pdoErrorInfo($pdo, $statement);

    $statement->execute([
        ':avatar' => $fileName,
        ':id' => $id
    ]);

    $_SESSION['messages'] = "Avatar updated";

    redirect('/editprofile.php');
}

if (isset($_POST['username'])) {
    $username = trim(filter_var($_POST['username'], FILTER_SANITIZE_STRING));

    if (!isValidUsername($pdo, $username)) {
        redirect('/editprofile.php');
    }

    $statement = $pdo->prepare('UPDATE users SET username = :username WHERE id = :id');
    pdoErrorInfo($pdo, $statement);

    $statement->execute([
        ':username' => $username,
        ':id' => $id
    ]);

    $_SESSION['messages'] = "Username updated";

    redirect('/editprofile.php');
}

if (isset($_POST['biography'])) {
    $biography = filter_var(trim($_POST['biography']), FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

    $statement = $pdo->prepare('UPDATE users SET biography = :biography WHERE id = :id');
    pdoErrorInfo($pdo, $statement);

    $statement->execute([
        ':biography' => $biography,
        ':id' => $id
    ]);

    $_SESSION['messages'] = "Biography updated";
    redirect('/editprofile.php');
}

// EMAIL
if (isset($_POST['email'])) {
    $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));

    if (existsInDatabase($pdo, 'users', 'email', $email)) {
        $_SESSION['errors'] = "This email is not avalible";
        redirect('/');
    }

    $statement = $pdo->prepare('UPDATE users SET email = :email WHERE id = :id');
    pdoErrorInfo($pdo, $statement);

    $statement->execute([
        ':email' => $email,
        ':id' => $id
    ]);

    $_SESSION['messages'] = "Email updated";

    redirect('/editprofile.php');
}

//PASSWORD
if (isset($_POST['oldPassword'], $_POST['newPassword'], $_POST['confirmNewPassword'])) {
    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmNewPassword = $_POST['confirmNewPassword'];

    if ($newPassword !== $confirmNewPassword) {
        $_SESSION['errors'] = "Passwords do not match";
        redirect('/editprofile.php');
    }
    // Has to be new password
    if ($oldPassword === $newPassword) {
        $_SESSION['errors'] = "This is already your current password";
        redirect('/editprofile.php');
    }

    $statement = $pdo->prepare('UPDATE users SET password = :password WHERE id = :id');
    pdoErrorInfo($pdo, $statement);

    $statement->execute([
        ':password' => password_hash($newPassword, PASSWORD_BCRYPT),
        ':id' => $id
    ]);

    $_SESSION['messages'] = "Password updated";

    redirect('/editprofile.php');
}

redirect('/');
