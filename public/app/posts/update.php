<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';

if (!userIsLoggedIn()) {
    redirect('/');
}

$id = $_SESSION['user']['id'];

if (isset($_FILES['image'], $_POST['id'])) {
    $image = $_FILES['image'];
    $post = getPostById($pdo, $_POST['id']);

    if (!isValidImage($image)) {
        redirect('/editpost.php?id=' . $post['id']);
    }

    // Puts uploaded images in uploads
    $fileName = createFileName($image['type']);

    if (!move_uploaded_file($image['tmp_name'], '../../uploads/posts/' . $fileName)) {
        $_SESSION['errors'] = "Oops!";
        redirect('/editpost.php?id=' . $post['id']);
    }

    unlink(__DIR__ . '/../../uploads/posts/' . $post['image']);

    $statement = $pdo->prepare('UPDATE posts SET image = :fileName WHERE id = :postId');
    pdoErrorInfo($pdo, $statement);

    $statement->execute([
        ':fileName' => $fileName,
        ':postId' => $post['id']
    ]);

    $_SESSION['messages'] = 'Success!';

    redirect('/editpost.php?id=' . $post['id']);
}

if (isset($_POST['description'], $_POST['id'])) {
    $description = filter_var(trim($_POST['description']), FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $post = getPostById($pdo, $_POST['id']);

    $statement = $pdo->prepare('UPDATE posts SET description = :description WHERE id = :postId');
    pdoErrorInfo($pdo, $statement);

    $statement->execute([
        ':description' => $description,
        ':postId' => $post['id']
    ]);

    $_SESSION['messages'] = 'Content updated';

    redirect('/editpost.php?id=' . $post['id']);
}

redirect('/');
