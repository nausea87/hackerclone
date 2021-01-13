<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';

if (!userIsLoggedIn()) {
    redirect('/');
}

$id = $_SESSION['user']['id'];

if (isset($_POST['id'])) {
    $post = getPostById($pdo, $_POST['id']);

    if ($post['user_id'] !== $id) {
        $_SESSION['errors'] = 'This is not your post';
        redirect('/');
    }


    //Post (working)
    $statement = $pdo->prepare('DELETE FROM posts WHERE id = :postId');
    pdoErrorInfo($pdo, $statement);

    unlink(__DIR__ . '/../../uploads/posts/' . $post['image']);

    $statement->execute([
        ':postId' => $post['id']
    ]);

    $_SESSION['messages'] = 'Post deleted';

    redirect('/profile.php?id=' . $id);

    //Likes (working)
    $statement = $pdo->prepare('DELETE FROM likes WHERE post_id = :postId');
    pdoErrorInfo($pdo, $statement);

    $statement->execute([
        ':postId' => $post['id']
    ]);
}

redirect('/');
