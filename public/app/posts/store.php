<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';

if (!userIsLoggedIn()) {
    redirect('/');
}
// Storing title, content, image from upload
if (isset($_POST['title'], $_POST['description'], $_FILES['image'])) {
    $title = filter_var(trim($_POST['title']), FILTER_SANITIZE_STRING);
    $description = filter_var(trim($_POST['description']), FILTER_SANITIZE_STRING);
    $image = $_FILES['image'];
    $id = $_SESSION['user']['id'];

    if (!isValidImage($image)) {
        redirect('/createpost.php');
    }

    $fileName = createFileName($image['type']);

    if (!move_uploaded_file($image['tmp_name'], '../../uploads/posts/' . $fileName)) {
        $_SESSION['errors'] = "Something went wrong with the upload";
        redirect('/createpost.php');
    }

    $statement = $pdo->prepare('INSERT INTO posts (user_id, image, description, title) VALUES (:user_id, :image, :description, :title)');

    if (!$statement) {
        die(var_dump($pdo->errorInfo()));
    }

    $statement->execute([
        ':user_id' => $id,
        ':image' => $fileName,
        ':title' => $title,
        ':description' => $description

    ]);

    // Redirect back to main after post successfull.
    $_SESSION['messages'] = "post uploaded!";
    redirect('/');
}

redirect('/');
